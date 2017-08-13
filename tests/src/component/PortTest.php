<?php

namespace pedroac\url\component;

use PHPUnit\Framework\TestCase;

class PortTest extends TestCase
{
    /**
     * @covers pedroac\url\component\Port::__toString
     * @covers pedroac\url\component\Port::__construct
     */
    public function testTooString()
    {
        $this->assertEquals('', (string)new Port);
        $this->assertEquals('80', (string)new Port(80));
    }

    /**
     * @covers pedroac\url\component\Port::toURIString
     */
    public function testToURIString()
    {
        $this->assertEquals(':80', (new Port(80))->toURIString());
        $this->assertEquals('', (new Port)->toURIString());
    }

    /**
     * @covers pedroac\url\component\Port::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertTrue((new Port)->isEmpty());
        $this->assertFalse((new Port(80))->isEmpty());
    }

    /**
     * @covers pedroac\url\component\Port::getNumber
     */
    public function testGetNumber()
    {
        $this->assertEquals(80, (new Port(80))->getNumber());
        $this->assertEquals(0, (new Port)->getNumber());
    }
}
