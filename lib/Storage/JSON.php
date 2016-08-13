<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2014-2016 Marius Sarca
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

class JSON extends File
{
    /**
     * JSON constructor.
     * @param string $path
     * @param string $prefix
     */
    public function __construct(string $path, string $prefix = '')
    {
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
        $config = json_encode($config);
        $this->fileWrite($file, $config);
    }
}
