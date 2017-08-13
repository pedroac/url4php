<?php

namespace pedroac\url\component;

use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase
{
    /**
     * @covers pedroac\url\component\Parameters::toArray
     * @covers pedroac\url\component\Parameters::__construct
     */
    public function testToArray()
    {
        $this->assertEquals([], (new Parameters([]))->toArray());

        $array1 = array('a' => 'hello', 'b' => 'world');
        $this->assertEquals($array1, (new Parameters($array1))->toArray());

        $array2 = array('a', 'b' => 'world');
        $this->assertEquals($array2, (new Parameters($array2))->toArray());
    }

    /**
     * @covers pedroac\url\component\Parameters::get
     */
    public function testGet()
    {
        $array1 = array('this' => 'hello', 'test' => 'world');
        $this->assertEquals(null, (new Parameters($array1))->get('notfound'));
        $this->assertEquals('world', (new Parameters($array1))->get('test'));
    }

    /**
     * @covers pedroac\url\component\Parameters::has
     */
    public function testHas()
    {
        $array1 = array('this' => 'hello', 'test' => 'world');

        $this->assertTrue((new Parameters($array1))->has('test'));

        $this->assertFalse((new Parameters($array1))->has('no'));
    }

    /**
     * @covers pedroac\url\component\Parameters::add
     */
    public function testAdd()
    {
        $array1 = array('a' => '1', 'b' => '2');
        $parameters = new Parameters($array1);
        $parameters->add(['c' => 3]);
        $this->assertEquals(['a' => '1', 'b' => '2', 'c' => '3'], $parameters->toArray());
    }

    /**
     * @covers pedroac\url\component\Parameters::remove
     */
    public function testRemove()
    {
        $parameters = new Parameters(['a' => '1', 'b' => '2', 'c' => '3']);
        $parameters->remove(['c', 'b']);
        $this->assertEquals(['a' => '1'], $parameters->toArray());
    }

    /**
     * @covers pedroac\url\component\Parameters::filter
     */
    public function testFilter()
    {
        $parameters = new Parameters(['a' => '1', 'b' => '2', 'c' => '3']);
        $parameters->filter(['c', 'b']);
        $this->assertEquals(['b' => '2', 'c' => '3'], $parameters->toArray());
    }
}
