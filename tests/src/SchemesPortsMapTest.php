<?php

namespace pedroac\url;

use PHPUnit\Framework\TestCase;

class SchemesPortsMapTest extends TestCase
{
    /**
     * @covers pedroac\url\SchemesPortsMap::__construct
     * @covers pedroac\url\SchemesPortsMap::isSchemePort
     */
    public function testIsSchemePort()
    {
        $map = new SchemesPortsMap(['abc' => 60, 'defg' => 55]);
        $this->assertTrue($map->isSchemePort('abc', 60));
        $this->assertTrue($map->isSchemePort('defg', 55));
        $this->assertFalse($map->isSchemePort('defg', 60));
        $this->assertFalse($map->isSchemePort('hhh', 55));
    }

    /**
     * @covers pedroac\url\SchemesPortsMap::default
     * @covers pedroac\url\SchemesPortsMap::isSchemePort
     */
    public function testDefault()
    {
        $map = SchemesPortsMap::default();
        $this->assertTrue($map->isSchemePort('http', 80));
        $this->assertTrue($map->isSchemePort('https', 443));
        $this->assertFalse($map->isSchemePort('http', 443));
        $this->assertFalse($map->isSchemePort('hhh', 80));
    }

    /**
     * @covers pedroac\url\SchemesPortsMap::fromFile
     * @covers pedroac\url\SchemesPortsMap::isSchemePort
     */
    public function testFromFile()
    {
        $map = SchemesPortsMap::fromFile(__DIR__.'/../data/schemes.csv');
        $this->assertTrue($map->isSchemePort('http', 80));
        $this->assertTrue($map->isSchemePort('https', 443));
        $this->assertFalse($map->isSchemePort('http', 443));
        $this->assertFalse($map->isSchemePort('hhh', 80));
    }
}