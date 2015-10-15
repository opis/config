<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2014-2015 Marius Sarca
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

namespace Opis\Config\Storage;

use RuntimeException;
use Opis\Config\ArrayHelper;
use Opis\Config\StorageInterface;

class File implements StorageInterface
{
    protected $path;
    
    protected $prefix;
    
    protected $extension;
    
    protected $cache = array();
    
    public function __construct($path, $prefix = '', $extension = 'conf')
    {
        $this->path = rtrim($path, '/');
        $this->prefix = trim($prefix, '.');
        $this->extension = trim($extension, '.');
        
        if($this->prefix !== '')
        {
            $this->prefix .= '.';
        }
        
        if($this->extension !== '')
        {
            $this->extension = '.' . $this->extension;
        }
        
        if(!is_dir($this->path) && !@mkdir($this->path, 0775, true))
        {
            throw new RuntimeException(vsprintf("Config directory ('%s') does not exist.", array($this->path)));
        }
        
        if(!is_writable($this->path) || !is_readable($this->path))
        {
            throw new RuntimeException(vsprintf("Config directory ('%s') is not writable or readable.", array($this->path)));
        }
        
    }
    
    protected function configFile($key)
    {
        return $this->path . '/' . $this->prefix . $key . $this->extension;
    }
    
    protected function fileWrite(&$file, &$data)
    {
        $fh = fopen($file, 'c');
        flock($fh, LOCK_EX);
        chmod($file, 0774);
        ftruncate($fh, 0);
        fwrite($fh, $data);
        flock($fh, LOCK_UN);
        fclose($fh);
    }
    
    protected function readConfig($file)
    {
        return unserialize(file_get_contents($file));
    }
    
    protected function writeConfig($file, array $config)
    {
        $config = serialize($config);
        $this->fileWrite($file, $config);
    }
    
    public function write($name, $value)
    {
        $path = explode('.', $name);
        $key = $path[0];
        $file = $this->configFile($key);
        
        if(!isset($this->cache[$key]))
        {
            if(file_exists($file))
            {
                $this->cache[$key] = new ArrayHelper($this->readConfig($file));
            }
            else
            {
                $this->cache[$key] = new ArrayHelper();
            }
            
        }
        
        $this->cache[$key]->set($path, $value);
        $this->writeConfig($file, $this->cache[$key]->toArray());
    }
    
    public function read($name, $default = null)
    {
        $path = explode('.', $name);
        $key = $path[0];
        
        if(!isset($this->cache[$key]))
        {
            $file = $this->configFile($key);
            
            if(!file_exists($file))
            {
                return $default;
            }
            
            $this->cache[$key] = new ArrayHelper($this->readConfig($file));
            
        }
        
        return $this->cache[$key]->get($path, $default);
    }
    
    public function has($name)
    {
        $path = explode('.', $name);
        $key = $path[0];
        
        if(!isset($this->cache[$key]))
        {
            $file = $this->configFile($key);
            
            if(!file_exists($file))
            {
                return false;
            }
            
            $this->cache[$key] = new ArrayHelper($this->readConfig($file));
        }
        
        return $this->cache[$key]->has($path);
    }
    
    public function delete($name)
    {
        $path = explode('.', $name);
        $key = $path[0];
        $file = $this->configFile($key);
        
        if(count($path) === 1)
        {
            if(file_exists($file))
            {
                if(unlink($file))
                {
                    unset($this->cache[$key]);
                    return true;
                }
                
                return false;
            }
            
            return false;
        }
        
        if(!isset($this->cache[$key]))
        {   
            if(!file_exists($file))
            {
                return false;
            }
            
            $this->cache[$key] = new ArrayHelper($this->readConfig($file));
        }
        
        if($this->cache[$key]->delete($path))
        {
            $this->writeConfig($file, $this->cache[$key]->toArray());
            return true;
        }
        
        return false;
    }
    
}
