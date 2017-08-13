<?php

namespace pedroac\url\component;

use PHPUnit\Framework\TestCase;

class FragmentTest extends TestCase
{
    /**
     * @covers pedroac\url\component\Fragment::__toString
     * @covers pedroac\url\component\Fragment::__construct
     */
    public function testToString()
    {
        $this->assertEquals('label', (string)new Fragment('label'));
        $this->assertEquals('', (string)new Fragment(''));
        $this->assertEquals('', (string)new Fragment(null));
    }

    /**
     * @covers pedroac\url\component\Fragment::toURIString
     */
    public function testToURIString()
    {
        $this->assertEquals('#label', (new Fragment('label'))->toURIString());
        $this->assertEquals('#', (new Fragment(''))->toURIString());
        $this->assertEquals('', (new Fragment(null))->toURIString());
    }

    /**
     * @covers pedroac\url\component\Fragment::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertTrue((new Fragment(null))->isEmpty());
        $this->assertTrue((new Fragment(''))->isEmpty());
        $this->assertFalse((new Fragment('label'))->isEmpty());
    }
}
