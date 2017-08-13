<?php

namespace pedroac\url\component;

use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{

    /**
     * @covers pedroac\url\component\Query::fromArray
     * @covers pedroac\url\component\Query::__construct
     */
    public function testFromArray()
    {
        $this->assertEquals('', Query::fromArray([]));
        $this->assertEquals('myparam=myarg', Query::fromArray(['myparam' => 'myarg']));
        $this->assertEquals('a=1&b=2&c=3', Query::fromArray(['a' => '1', 'b' => '2', 'c' => '3']));
        $this->assertEquals('a%5B0%5D=bc', Query::fromArray(['a' => ['bc']]));
        $this->assertEquals('a%5B0%5D%5B0%5D=bc', Query::fromArray(['a' => [['bc']]]));
        $this->assertEquals('a%5B0%5D=bc&a%5B1%5D=de', Query::fromArray(['a' => ['bc','de']]));
        $this->assertEquals('a%5Bmk%5D=bc', Query::fromArray(['a' => ['mk' => 'bc']]));
    }

    /**
     * @covers pedroac\url\component\Query::fromParameters
     */
    public function testFromParameters()
    {
        $this->assertEquals('', Query::fromParameters(new Parameters()));
        $this->assertEquals('myparam=myarg', Query::fromParameters(new Parameters(['myparam' => 'myarg'])));
    }

    /**
     * @covers pedroac\url\component\Query::__toString
     * @covers pedroac\url\component\Query::__construct
     */
    public function testToString()
    {
        $this->assertEquals('', (string)new Query);
        $this->assertEquals('', (string)new Query(''));
        $this->assertEquals('myparam=myarg', (string)new Query('myparam=myarg'));
        $this->assertEquals('a=1&b=2&c=3', (string)new Query('a=1&b=2&c=3'));
        $this->assertEquals('a[0]=bc', (string)new Query('a[0]=bc'));
        $this->assertEquals('a[0][0]=bc', (string)new Query('a[0][0]=bc'));
        $this->assertEquals('a[0]=bc&a[1]=de', (string)new Query('a[0]=bc&a[1]=de'));
        $this->assertEquals('a[mk]=bc', (string)new Query('a[mk]=bc'));
    }

    /**
     * @covers pedroac\url\component\Query::toURIString
     * @covers pedroac\url\component\Query::__construct
     * @todo Test unicode edge cases.
     */
    public function testToURIString()
    {
        $this->assertEquals('', (new Query(null))->toURIString());
        $this->assertEquals('?', (new Query(''))->toURIString());
        $this->assertEquals('?myparam=myarg', (new Query('myparam=myarg'))->toURIString());
        $this->assertEquals('?a=1&b=2&c=3', (new Query('a=1&b=2&c=3'))->toURIString());
        $this->assertEquals('?a%5B0%5D=bc', (new Query('a[0]=bc'))->toURIString());
        $this->assertEquals('?a%5B0%5D%5B0%5D=bc', (new Query('a[0][0]=bc'))->toURIString());
        $this->assertEquals('?a%5B0%5D=bc&a%5B1%5D=de', (new Query('a[0]=bc&a[1]=de'))->toURIString());
        $this->assertEquals('?a%5Bmk%5D=bc', (new Query('a[mk]=bc'))->toURIString());
        $this->assertEquals('?q', (new Query('q'))->toURIString());
    }

    /**
     * @covers pedroac\url\component\Query::isEmpty
     * @covers pedroac\url\component\Query::__construct
     */
    public function testIsEmpty()
    {
        $this->assertTrue((new Query())->isEmpty());
        $this->assertTrue((new Query(''))->isEmpty());
        $this->assertFalse((new Query('a=1'))->isEmpty());
    }

    /**
     * @covers pedroac\url\component\Query::toArray
     * @covers pedroac\url\component\Query::__construct
     */
    public function testToArray()
    {
        $this->assertEquals([], (new Query())->toArray());
        $this->assertEquals(['myparam' => 'myarg'], (new Query('myparam=myarg'))->toArray());
        $this->assertEquals(['a' => '1', 'b' => '2', 'c' => '3'], (new Query('a=1&b=2&c=3'))->toArray());
        $this->assertEquals(['a' => ['bc']], (new Query('a[0]=bc'))->toArray());
        $this->assertEquals(['a' => [['bc']]], (new Query('a[0][0]=bc'))->toArray());
        $this->assertEquals(['a' => ['bc','de']], (new Query('a[0]=bc&a[1]=de'))->toArray());
        $this->assertEquals(['a' => ['mk' => 'bc']], (new Query('a[mk]=bc'))->toArray());
        $this->assertEquals(['q' => ''], (new Query('q'))->toArray());

        $query = (new Query('a=1&b=2'));
        $this->assertEquals(['a' => 1, 'b' => 2], $query->toArray());
        $this->assertEquals(['a' => 1, 'b' => 2], $query->toArray());
    }

    /**
     * @covers pedroac\url\component\Query::toParameters
     * @covers pedroac\url\component\Query::__construct
     */
    public function testToParameters()
    {
        $this->assertEquals(new Parameters(), (new Query())->toParameters());

        $this->assertEquals(new Parameters(['myparam' => 'myarg']),
                            (new Query('myparam=myarg'))->toParameters());

        $this->assertEquals(new Parameters(['a' => '1', 'b' => '2', 'c' => '3']),
                            (new Query('a=1&b=2&c=3'))->toParameters());

        $this->assertEquals(new Parameters(['a' => ['bc']]),
                            (new Query('a[0]=bc'))->toParameters());

        $this->assertEquals(new Parameters(['a' => [['bc']]]),
                            (new Query('a[0][0]=bc'))->toParameters());

        $this->assertEquals(new Parameters(['a' => ['bc','de']]),
                            (new Query('a[0]=bc&a[1]=de'))->toParameters());

        $this->assertEquals(new Parameters(['a' => ['mk' => 'bc']]),
                            (new Query('a[mk]=bc'))->toParameters());
    }

    /**
     * @covers pedroac\url\component\Query::get
     * @covers pedroac\url\component\Query::__construct
     */
    public function testGet()
    {
        $this->assertEquals('myarg', (new Query('myparam=myarg'))->get('myparam'));
        $this->assertEquals(null, (new Query('myparam=myarg'))->get('zzz'));
        $this->assertEquals('1', (new Query('a=1&b=2'))->get('a'));
        $this->assertEquals('2', (new Query('a=1&b=2'))->get('b'));
        $this->assertEquals(['1'], (new Query('a[0]=1&b=2'))->get('a'));
    }

    /**
     * @covers pedroac\url\component\Query::has
     * @covers pedroac\url\component\Query::__construct
     */
    public function testHas()
    {
        $this->assertFalse((new Query())->has('a'));
        $this->assertTrue((new Query('a=1'))->has('a'));
        $this->assertTrue((new Query('a=1&b=2'))->has('b'));
        $this->assertFalse((new Query('a=1&b=2'))->has('c'));
        $this->assertTrue((new Query('a[0]=1&b=2'))->has('a'));
    }

    /**
     * @covers pedroac\url\component\Query::append
     * @covers pedroac\url\component\Query::__construct
     */
    public function testAppend()
    {
        $this->assertEquals('a=1&b=2&c=3', (new Query('a=1&b=2'))->append('c=3'));
        $this->assertEquals('a=1&b=2&c=3', (new Query('a=1&b=2'))->append(new Query('c=3')));
    }
    
    /**
     * @covers pedroac\url\component\Query::change
     * @covers pedroac\url\component\Query::__construct
     */
    public function testChange()
    {
        $this->assertEquals('', (new Query())->change([]));
        $this->assertEquals('a=1', (new Query())->change(['a' => 1]));
        $this->assertEquals('', (new Query('a=1'))->change(['a' => null]));
        $this->assertEquals('a=1&b=2', (new Query('a=1'))->change(['b' => 2]));
        $this->assertEquals('a=1', (new Query('a=1'))->change(['b' => null]));
        $this->assertEquals('a=2', (new Query('a=1'))->change(['a' => 2]));
        $this->assertEquals('a=2&b=3', (new Query('a=1&b=1'))->change(['a' => 2, 'b' => 3]));
        $this->assertEquals('', (new Query('a=1'))->change([], ['a']));
        $this->assertEquals('b=5', (new Query('a=1'))->change(['b' => 5], ['a']));
    }
}
