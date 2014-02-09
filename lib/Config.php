<?php

namespace Opis\Config;

class Config
{
    protected $loader;
    
    protected $config;
    
    public function __construct(ConfigLoaderInterface $loader)
    {
        $this->loader = $loader;
        $config = new ArrayHelper($this->loader->load());
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
