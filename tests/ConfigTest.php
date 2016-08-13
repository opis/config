<?php

namespace Opis\Config\Test;


use PHPUnit\Framework\TestCase;
use Opis\Config\ConfigHelper;

class ConfigTest extends TestCase
{
    public function testHelper()
    {

        $helper = new ConfigHelper([
            'a' => [
                'a1' => 4,
                'a2' => [1, 2, 3]
            ],
            'b' => (object)[
                'b1' => ['a', 'b', 'c'],
                'b2' => "config"
            ],
        ]);

        $this->assertEquals(4, $helper->get('a.a1'));
        $this->assertEquals(4, $helper->get(['a', 'a1']));

        $this->assertEquals("config", $helper->get('b.b2'));
        $this->assertEquals("b", $helper->get('b.b1.1'));

        $this->assertTrue(is_object($helper->get('b')));

        $this->assertFalse($helper->has('a.a3'));
        $helper->set('a.a3', 5);
        $this->assertTrue($helper->has('a.a3'));

        $this->assertTrue($helper->delete('a'));
        $this->assertFalse($helper->has('a'));

        $helper->set('a.a1.a2.a3', (object) ['ax' => 1, 'ay' => 2]);

        $this->assertTrue($helper->has('a.a1.a2.a3.ax'));
        $this->assertEquals(2, $helper->get('a.a1.a2.a3.ay'));

    }
}