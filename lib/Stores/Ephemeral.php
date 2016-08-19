<?php
/* ===========================================================================
 * Copyright 2013-2016 The Opis Project
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

namespace Opis\Config\Stores;

use Opis\Config\ConfigInterface;
use Opis\Config\ConfigHelper;

class Ephemeral implements ConfigInterface
{

    protected $config;

    /**
     * Memory constructor.
     * @param array|object $config
     */
    public function __construct($config = [])
    {
        $this->config = new ConfigHelper($config);
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $name, $value) : bool
    {
        return $this->config->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $name, $default = null)
    {
        return $this->config->get($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name) : bool
    {
        return $this->config->has($name);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $name) : bool
    {
        return $this->config->delete($name);
    }
}
