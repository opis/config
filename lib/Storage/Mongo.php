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

class Mongo implements StorageInterface
{
    /** @var \MongoCollectio Collection. */
    protected $mongo;
    
    /** @var array Columns mapping. */
    protected $columns;
    
    /** @var array Cache. */
    protected $cache = array();
    
    public function __construct(MongoCollection $mongo, array $columns = array())
    {
        $this->mongo = $mongo;
        $this->columns = $columns + array(
          'name' => 'name',
          'data' => 'data',
        );
    }
    
    protected function checkCache($name)
    {
        if (!isset($this->cache[$name]))
        {
            $config = $this->mongo->findOne(array(
                $this->columns['name'] => $name,
            ));
            
            if (!$config)
            {
                return false;
            }
            
            $this->cache[$name] = new ArrayHelper(unserialize($config[$this->columns['data']]));
        }
        
        return true;
    }
    
    protected function updateRecord($name)
    {
        return (bool) $this->mongo->update(
            array(
                $this->columns['name'] => $name,
            ),
            array(
                '$set' => array(
                    $this->columns['name'] => $name,
                    $this->columns['data'] => serialize($this->cache[$name]->toArray()),
                )
            ),
            array(
                'upsert' => true,
            )
        );
    }
    
    protected function deleteRecord($name)
    {
        return (bool) $this->mongo->remove(array(
           $this->columns['name'] => $name, 
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
        }
        else
        {
            $this->cache[$key] = new ArrayHelper($value);
        }
        
        return $this->updateRecord($key);
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
                return $this->updateRecord($key);
            }
            return false;
        }

        unset($this->cache[$key]);
        
        return $this->deleteRecord($key);
    }
    
}
