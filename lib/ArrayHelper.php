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

class ArrayHelper
{
    protected $config;
    
    public function __construct(array $config = array())
    {
        $this->config = $config;
    }
    
    public function get($name, $default = null)
    {
        $config = &$this->config;
        
        $path = is_array($name) ? $name : explode('.', $name);
        
        foreach($path as &$key)
        {
            if(!is_array($config) || !isset($config[$key]))
            {
                return $default;
            }
            
            $config = &$config[$key];
        }
        
        return $config;
    }
    
    public function set($name, $value)
    {
        $config = &$this->config;
        $path = is_array($name) ? $name : explode('.', $name);
        $last = array_pop($path);
        
        foreach($path as &$key)
        {
            if(!isset($config[$key]))
            {
                $config[$key] = array();
            }
            $config = &$config[$key];
        }
        
        $config[$last] = $value;
    }
    
    public function has($name)
    {
        return $this !== $this->get($name, $this);
    }
    
    public function delete($name)
    {
        $config = &$this->config;
        $path = is_array($name) ? $name : explode('.', $name);
        $last = array_pop($path);
        
        foreach($path as &$key)
        {
            if(!isset($config[$key]))
            {
                return false;
            }
            $config = &$config[$key];
        }
        
        if(!isset($config[$last]))
        {
            return false;
        }
        
        unset($config[$last]);
        return true;
    }
    
    public function toArray()
    {
        return $this->config;
    }
    
}
