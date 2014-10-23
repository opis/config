<?php
/* ===========================================================================
* Opis Project
* http://opis.io
* ===========================================================================
* Copyright 2014 Marius Sarca
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
* ============================================================================ */

namespace Opis\Config\Storage;

use Opis\Config\StorageInterface;

class DualStorage implements StorageInterface
{
  
    protected $primary;
    
    protected $secondary;
    
    protected $dummy;
    
    protected $autoSync;
  
    public function __construct(StorageInterface $primary, StorageInterface $secondary, $autosync = true)
    {
        $this->primary = $primary;
        $this->secondary = $secondary;
        $this->autoSync = $autosync;
        $this->dummy = spl_object_hash($this) . ':' . uniqid();
    }
  
    public function write($name, $value)
    {
        $this->primary->write($name, $value);
        $this->secondary->write($name, $value);
    }
    
    public function read($name, $default = null)
    {
        $val = $this->primary->read($name, $this->dummy);
        
        if ($val === $this->dummy)
        {
            $val = $this->secondary->read($name, $default);
            
            if ($this->autoSync)
            {
                $this->primary->write($name, $val);
            }
        }
        
        return $val;
    }
    
    public function has($name)
    {
        return $this->primary->has($name) || $this->secondary->has($name);
    }
    
    public function delete($name)
    {
        $p = $this->primary->delete($name);
        $s = $this->secondary->delete($name);
        return $p && $s;
    }
}
