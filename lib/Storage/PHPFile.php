<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
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

class PHPFile extends File
{
    
    public function __construct($path, $prefix = '')
    {
        parent::__construct($path, $prefix, '.php');
    }
    
    protected function readConfig($file)
    {
        return include $file;
    }
    
    protected function writeConfig($file, array $config)
    {
        file_put_contents(
            $file,
            "<?php\n\rreturn " . preg_replace('/\s=>\s(\n\s+)array\s\(\n/', " => array (\n", var_export($config, TRUE)) . ';'
        );
    }
}
