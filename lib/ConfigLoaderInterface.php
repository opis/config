<?php

namespace Opis\Config;

interface ConfigLoaderInterface
{
    public function load();
    
    public function save(array $config);
}