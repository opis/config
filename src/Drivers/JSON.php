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

namespace Opis\Config\Drivers;

class JSON extends File
{
    const DEFAULT_ENCODE_OPTIONS = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES;

    /** @var int */
    protected $encodeOptions = 0;

    /**
     * JSON constructor.
     * @param string $path
     * @param string $prefix
     * @param int $encode_options
     */
    public function __construct(string $path, string $prefix = '', int $encode_options = self::DEFAULT_ENCODE_OPTIONS)
    {
        $this->encodeOptions = $encode_options;
        parent::__construct($path, $prefix, 'json');
    }

    /**
     * {@inheritdoc}
     */
    protected function readConfig(string $file)
    {
        return json_decode(file_get_contents($file));
    }

    /**
     * {@inheritdoc}
     */
    protected function writeConfig(string $file, $config)
    {
        $config = json_encode($config, $this->encodeOptions);
        $this->fileWrite($file, $config);
    }
}
