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

namespace Opis\Config\Storage;

use RuntimeException;
use Opis\Config\ConfigHelper;
use Opis\Config\ConfigInterface;

class File implements ConfigInterface
{
    /** @var string */
    protected $path;

    /** @var string */
    protected $prefix;

    /** @var string */
    protected $extension;

    /** @var array */
    protected $cache = [];

    /**
     * File constructor.
     * @param string $path
     * @param string $prefix
     * @param string $extension
     */
    public function __construct(string $path, string $prefix = '', string $extension = 'conf')
    {
        $this->path = rtrim($path, '/');
        $this->prefix = trim($prefix, '.');
        $this->extension = trim($extension, '.');

        if ($this->prefix !== '') {
            $this->prefix .= '.';
        }

        if ($this->extension !== '') {
            $this->extension = '.' . $this->extension;
        }

        if (!is_dir($this->path) && !@mkdir($this->path, 0775, true)) {
            throw new RuntimeException(vsprintf("Config directory ('%s') does not exist.", [$this->path]));
        }

        if (!is_writable($this->path) || !is_readable($this->path)) {
            throw new RuntimeException(vsprintf("Config directory ('%s') is not writable or readable.", [$this->path]));
        }

    }

    /**
     * @param string $key
     * @return string
     */
    protected function configFile(string $key) : string
    {
        return $this->path . '/' . $this->prefix . $key . $this->extension;
    }

    /**
     * @param string $file
     * @param string $data
     */
    protected function fileWrite(string &$file, string &$data)
    {
        $chmod = !file_exists($file);
        $fh = fopen($file, 'c');
        flock($fh, LOCK_EX);
        if ($chmod) {
            chmod($file, 0774);
        }
        ftruncate($fh, 0);
        fwrite($fh, $data);
        flock($fh, LOCK_UN);
        fclose($fh);
    }

    /**
     * @param string $file
     * @return mixed
     */
    protected function readConfig(string $file)
    {
        return unserialize(file_get_contents($file));
    }

    /**
     * @param string $file
     * @param array|object $config
     */
    protected function writeConfig(string $file, $config)
    {
        $config = serialize($config);
        $this->fileWrite($file, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $name, $value) : bool
    {
        $path = explode('.', $name);
        $key = $path[0];
        $file = $this->configFile($key);

        if (!isset($this->cache[$key])) {
            if (file_exists($file)) {
                $this->cache[$key] = new ConfigHelper($this->readConfig($file));
            } else {
                $this->cache[$key] = new ConfigHelper();
            }

        }

        $this->cache[$key]->set($path, $value);
        $this->writeConfig($file, $this->cache[$key]->getConfig());
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $name, $default = null)
    {
        $path = explode('.', $name);
        $key = $path[0];

        if (!isset($this->cache[$key])) {
            $file = $this->configFile($key);

            if (!file_exists($file)) {
                return $default;
            }

            $this->cache[$key] = new ConfigHelper($this->readConfig($file));

        }

        return $this->cache[$key]->get($path, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name) : bool
    {
        $path = explode('.', $name);
        $key = $path[0];

        if (!isset($this->cache[$key])) {
            $file = $this->configFile($key);

            if (!file_exists($file)) {
                return false;
            }

            $this->cache[$key] = new ConfigHelper($this->readConfig($file));
        }

        return $this->cache[$key]->has($path);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $name) : bool
    {
        $path = explode('.', $name);
        $key = $path[0];
        $file = $this->configFile($key);

        if (count($path) === 1) {
            if (file_exists($file)) {
                if (unlink($file)) {
                    unset($this->cache[$key]);
                    return true;
                }

                return false;
            }

            return false;
        }

        if (!isset($this->cache[$key])) {
            if (!file_exists($file)) {
                return false;
            }

            $this->cache[$key] = new ConfigHelper($this->readConfig($file));
        }

        if ($this->cache[$key]->delete($path)) {
            $this->writeConfig($file, $this->cache[$key]->getConfig());
            return true;
        }

        return false;
    }

}
