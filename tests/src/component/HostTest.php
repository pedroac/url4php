<?php

namespace pedroac\url\component;

use PHPUnit\Framework\TestCase;

class HostTest extends TestCase
{
    /**
     * @covers pedroac\url\component\Host::__toString
     * @covers pedroac\url\component\Host::__construct
     */
    public function testToString()
    {
        $this->assertEquals('myhost.com', (string)new Host('myhost.com'));
        $this->assertEquals('Myhost.COm', (string)new Host('Myhost.COm'));
        $this->assertEquals('127.0.0.8', (string)new Host('127.0.0.8'));
        $this->assertEquals('[2001:0db8:0000:0000:0000:ff00:0042:8329]',
                            (string)new Host('[2001:0db8:0000:0000:0000:ff00:0042:8329]'));
        $this->assertEquals('', new Host);
    }

    /**
     * @covers pedroac\url\component\Host::toURIString
     */
    public function testToURIString()
    {
        $this->assertEquals('myhost.com', (new Host('myhost.com'))->toURIString());
        $this->assertEquals('myhost.com', (new Host('Myhost.COm'))->toURIString());
        $this->assertEquals('127.0.0.8', (new Host('127.0.0.8'))->toURIString());
        $this->assertEquals('[2001:db8::ff00:42:8329]',
                            (new Host('[2001:0db8:0000:0000:0000:ff00:0042:8329]'))
                            ->toURIString());
        $this->assertEquals('', (new Host)->toURIString());
    }

    /**
     * @covers pedroac\url\component\Host::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertFalse((new Host('myhost.com'))->isEmpty());
        $this->assertFalse((new Host('27.0.0.8'))->isEmpty());
        $this->assertFalse((new Host('[2001:0db8:0000:0000:0000:ff00:0042:8329]'))->isEmpty());
        $this->assertTrue((new Host)->isEmpty());
    }

    /**
     * @covers pedroac\url\component\Host::toArray
     */
    public function testToArray()
    {
        $this->assertEquals([], (new Host)->toArray());
        $this->assertEquals(['127','0','0','8'], (new Host('127.0.0.8'))->toArray());
        $this->assertEquals(['www','google','com'], (new Host('www.google.com'))->toArray());
        $this->assertEquals(['2001','0db8','0000','0000','0000','ff00','0042','8329'],
                            (new Host('[2001:0db8:0000:0000:0000:ff00:0042:8329]'))->toArray());
    }

    /**
     * @covers pedroac\url\component\Host::getType
     */
    public function testGetType()
    {
        $this->assertEquals(Host::TYPE_EMPTY, (new Host)->getType());
        $this->assertEquals(Host::TYPE_IPV4, (new Host('127.0.0.8'))->getType());
        $this->assertEquals(Host::TYPE_NAME, (new Host('www.test.com'))->getType());
        $this->assertEquals(Host::TYPE_IPV6, (new Host('[2001:0db8:0000:0000:0000:ff00:0042:8329]'))->getType());
    }

    /**
     * @covers pedroac\url\component\Host::toPunycode
     */
    public function testToPunycode()
    {
        $this->assertEquals('127.0.0.1', (new Host('127.0.0.1'))->toPunycode());
        $this->assertEquals('xn--3cam7a.com', (new Host('ãéô.com'))->toPunycode());
        $this->assertEquals('xn--cndido-wta.com', (new Host('Cândido.com'))->toPunycode());
        $this->assertEquals('xn--cndido-wta.com', (new Host('cÂndido.com'))->toPunycode());
        $this->assertEquals('xn--c1yn36f', (new Host('點看'))->toPunycode());
    }

    /**
     * @covers pedroac\url\component\Host::toUnicode
     * @todo   Implement testToUnicode().
     */
    public function testToUnicode()
    {
        $this->assertEquals('127.0.0.1', (new Host('127.0.0.1'))->toUnicode());
        $this->assertEquals('ãéô.com', (new Host('xn--3cam7a.com'))->toUnicode());
        $this->assertEquals('點看', (new Host('xn--c1yn36f'))->toUnicode());
    }
}
