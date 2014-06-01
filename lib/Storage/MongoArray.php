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
use Opis\Config\ArrayHelper;
use MongoCollection;

class MongoArray implements StorageInterface
{
    /** @var \MongoCollectio Collection. */
    protected $mongo;
    
    /** @var array Cache. */
    protected $cache = array();
    
    public function __construct(MongoCollection $mongo)
    {
        $this->mongo = $mongo;
    }
    
    protected function checkCache($name)
    {
        if (!isset($this->cache[$name]))
        {
            $config = $this->mongo->findOne(array(
                '_id' => $name,
            ));
            
            if (!$config)
            {
                return false;
            }
            
            unset($config['_id']);
            
            $this->cache[$name] = new ArrayHelper($config);
        }
        
        return true;
    }
    
    protected function updateRecord($name, $key)
    {
        $opts = array();
        
        if ($key)
        {
            $opts = array($key);
            if ($this->cache[$name]->has($opts))
            {
                $set['$set'][$key] = $this->cache[$name]->get($opts);
            }
            else
            {
                $set['$unset'][$key] = true;
            }
            $opts = array('upsert' => true);
        }
        else
        {
            $set['$set'] = $this->cache[$name]->toArray();
        }
        
        $set['$set']['_id'] = $name;
        
        return (bool) $this->mongo->update(array('_id' => $name), $set, $opts);
    }
    
    protected function deleteRecord($name)
    {
        return (bool) $this->mongo->remove(array(
           '_id' => $name, 
        ));
    }
    
    public function write($name, $value)
    {
        $path = explode('.', $name);
        $key = array_shift($path);

        if ($path)
        {
            if (!$this->checkCache($key))
            {
                $this->cache[$key] = new ArrayHelper();
            }
            
            $this->cache[$key]->set($path, $value);
            
            return $this->updateRecord($key, $path[0]);
        }

        $this->cache[$key] = new ArrayHelper($value);
        $this->deleteRecord($key);
        return $this->updateRecord($key, false);
    }
    
    public function read($name, $default = null)
    {
        $path = explode('.', $name);
        $key = array_shift($path);
        
        if ($this->checkCache($key))
        {
            return $path ? $this->cache[$key]->get($path, $default) : $this->cache[$key]->toArray();
        }
        
        return $default;
    }
    
    public function has($name)
    {
        $path = explode('.', $name);
        $key = array_shift($path);
        
        if ($this->checkCache($key))
        {
            return $path ? $this->cache[$key]->has($path) : true;
        }
        
        return false;
    }
    
    public function delete($name)
    {
        $path = explode('.', $name);
        $key = array_shift($path);
        
        if ($path)
        {
            if ($this->checkCache($key) && $this->cache[$key]->delete($path))
            {
                return $this->updateRecord($key, $path[0]);
            }
            return false;
        }

        unset($this->cache[$key]);
        
        return $this->deleteRecord($key);
    }
    
}
