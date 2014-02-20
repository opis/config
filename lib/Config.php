<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
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
    protected $loader;
    
    protected $config;
    
    public function __construct(StorageInterface $loader)
    {
        $this->loader = $loader;
        $this->config = new ArrayHelper($this->loader->load());
    }
    
    public function write($name, $value)
    {
        return $this->config->set($name, $value);
    }
    
    public function read($name, $default = null)
    {
        return $this->config->get($name, $default);
    }
    
    public function has($name)
    {
        return $this->config->has($name);
    }
    
    public function delete($name)
    {
        return $this->config->delete($name);
    }
    
    public function save()
    {
        return $this->loader->save($this->config->toArray());
    }
}
