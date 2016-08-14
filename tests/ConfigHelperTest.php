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

namespace Opis\Config\Test;

use Opis\Config\ConfigHelper;
use PHPUnit\Framework\TestCase;

class ConfigHelperTest extends TestCase
{
    /** @var  ConfigHelper */
    protected $cfg;

    public function setUp()
    {
        $this->cfg = new ConfigHelper([
            'foo' => [
                'bar' => 'BAR',
                'baz' => 'BAZ',
            ],
            'bar' => [
                'foo' => 'FOO',
            ]
        ]);
    }

    public function testNotFound()
    {
        $this->assertEquals(null, $this->cfg->get('goo'));
        $this->assertEquals('bar', $this->cfg->get('goo', 'bar'));
    }

    public function testGet()
    {
        $this->assertEquals('BAR', $this->cfg->get('foo.bar'));
        $this->assertEquals(['foo' => 'FOO'], $this->cfg->get('bar'));
    }

    public function testHas()
    {
        $this->assertTrue($this->cfg->has('foo.bar'));
        $this->assertFalse($this->cfg->has('foo.qux'));
    }

    public function testSet()
    {
        $this->cfg->set('foo.qux', 'QUX');
        $this->assertEquals('QUX', $this->cfg->get('foo.qux'));
        $this->assertEquals([
            'bar' => 'BAR',
            'baz' => 'BAZ',
            'qux' => 'QUX'
        ], $this->cfg->get('foo'));
    }

    public function testDelete()
    {
        $this->assertTrue($this->cfg->delete('foo.bar'));
        $this->assertFalse($this->cfg->has('foo.bar'));
    }
}