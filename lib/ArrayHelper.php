<?php

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
        $path = explode('.', $name);
        
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
        $path = explode('.', $name);
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
        return null !== $this->get($name);
    }
    
    public function delete($name)
    {
        $config = &$this->config;
        $path = explode('.', $name);
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