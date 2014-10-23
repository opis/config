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
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Config;

class Config
{
    
    protected $storage;
    
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
    
    public function write($name, $value)
    {
        return $this->storage->write($name, $value);
    }
    
    public function read($name, $default = null)
    {
        return $this->storage->read($name, $default);
    }
    
    public function has($name)
    {
        return $this->storage->has($name);
    }
    
    public function delete($name)
    {
        return $this->storage->delete($name);
    }
    
}
