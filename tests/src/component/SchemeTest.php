<?php

namespace pedroac\url\component;

use PHPUnit\Framework\TestCase;

class SchemeTest extends TestCase
{

    /**
     * @covers pedroac\url\component\Scheme::__toString
     * @covers pedroac\url\component\Scheme::__construct
     */
    public function testToString()
    {
        $this->assertEquals('http', (string)new Scheme('http'));
        $this->assertEquals('HtTp', (string)new Scheme('HtTp'));
        $this->assertEquals('', (string)new Scheme);
    }

    /**
     * @covers pedroac\url\component\Scheme::toURIString
     */
    public function testToURIString()
    {
        $this->assertEquals('http:', (new Scheme('http'))->toURIString());
        $this->assertEquals('http:', (new Scheme('HtTp'))->toURIString());
        $this->assertEquals('', (new Scheme)->toURIString());
    }

    /**
     * @covers pedroac\url\component\Scheme::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertFalse((new Scheme('http'))->isEmpty());
        $this->assertTrue((new Scheme)->isEmpty());
    }
}
