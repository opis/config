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

namespace Opis\Config;

class ConfigHelper
{
    protected $config;

    /**
     * ArrayHelper constructor.
     * @param array|object $config
     */
    public function __construct($config = [])
    {
        if (!is_object($config) && !is_array($config)) {
            $config = [];
        }
        $this->config = $config;
    }


    /**
     * @param array|string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $config = &$this->config;

        $path = is_array($name) ? $name : explode('.', $name);

        foreach ($path as &$key) {
            if (is_object($config)) {
                if (!isset($config->{$key})) {
                    return $default;
                }
                $config = &$config->{$key};
            } elseif (is_array($config)) {
                if (!isset($config[$key])) {
                    return $default;
                }
                $config = &$config[$key];
            } else {
                return $default;
            }
        }

        return $config;
    }

    /**
     * @param array|string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $config = &$this->config;
        $path = is_array($name) ? $name : explode('.', $name);
        $last = array_pop($path);

        foreach ($path as &$key) {
            if (is_object($config)) {
                if (!isset($config->{$key})) {
                    $config->{$key} = [];
                }
                $config = &$config->{$key};
                continue;
            }

            if (!isset($config[$key])) {
                $config[$key] = [];
            }

            $config = &$config[$key];
        }

        if (is_object($config)) {
            $config->{$last} = $value;
        } else {
            $config[$last] = $value;
        }
    }

    /**
     * @param array|string $name
     * @return bool
     */
    public function has($name) : bool
    {
        return $this !== $this->get($name, $this);
    }

    /**
     * @param array|string $name
     * @return bool
     */
    public function delete($name) : bool
    {
        $config = &$this->config;
        $path = is_array($name) ? $name : explode('.', $name);
        $last = array_pop($path);

        foreach ($path as &$key) {
            if (is_object($config)) {
                if (!isset($config->{$key})) {
                    return false;
                }
                $config = &$config->{$key};
                continue;
            }
            if (!isset($config[$key])) {
                return false;
            }
            $config = &$config[$key];
        }

        if (is_object($config)) {
            if (!isset($config->{$last})) {
                return false;
            }
            unset($config->{$last});
            return true;
        }

        if (!isset($config[$last])) {
            return false;
        }
        unset($config[$last]);
        return true;
    }

    /**
     * @return array|object
     */
    public function getConfig()
    {
        return $this->config;
    }

}
