<?php

namespace pedroac\url;

use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @covers pedroac\url\Base::__toString
     * @covers pedroac\url\Base::__construct
     */
    public function testToString()
    {
        $this->assertEquals('http://a.com/abc/def?a=1',
                            (string)new Base(new URL('http://a.com/abc/def?a=1')));

        $this->expectException(\InvalidArgumentException::class);
        new Base(new URL('/abc/def?a=1'));
    }

    /**
     * @covers pedroac\url\Base::__toString
     * @covers pedroac\url\Base::fromString
     */
    public function testFromString()
    {
        $this->assertEquals('http://a.com/abc/def?a=1',
                            (string)Base::fromString('http://a.com/abc/def?a=1'));
    }

    /**
     * @covers pedroac\url\Base::toAbsolute
     * @covers pedroac\url\Base::fromString
     * @see https://tools.ietf.org/html/rfc3986#section-5.4.1
     * @see https://tools.ietf.org/html/rfc3986#section-5.4.2
     * @todo Test with unicode edge cases.
     */
    public function testToAbsolute()
    {
        
        $base = Base::fromString('http://a/b/c/d;p?q');
        $this->assertEquals('http://a/b/c/d;p?q', $base->toAbsolute(new URL('')));
        $this->assertEquals('http://e/d', $base->toAbsolute(new URL('http://e/d')));
        $this->assertEquals('http://e/d', $base->toAbsolute(new URL('//e/d')));
        $this->assertEquals('g:h', $base->toAbsolute(new URL('g:h')));
        $this->assertEquals('http://a/b/c/g', $base->toAbsolute(new URL('g')));
        $this->assertEquals('http://a/b/c/g', $base->toAbsolute(new URL('./g')));
        $this->assertEquals('http://a/b/c/g/', $base->toAbsolute(new URL('g/')));
        $this->assertEquals('http://a/g', $base->toAbsolute(new URL('/g')));
        $this->assertEquals('http://a/b/c/d;p?y', $base->toAbsolute(new URL('?y')));
        $this->assertEquals('http://a/b/c/g?y', $base->toAbsolute(new URL('g?y')));
        $this->assertEquals('http://a/b/c/d;p?q#s', $base->toAbsolute(new URL('#s')));
        $this->assertEquals('http://a/b/c/g#s', $base->toAbsolute(new URL('g#s')));
        $this->assertEquals('http://a/b/c/g?y#s', $base->toAbsolute(new URL('g?y#s')));
        $this->assertEquals('http://a/b/c/;x', $base->toAbsolute(new URL(';x')));
        $this->assertEquals('http://a/b/c/g;x', $base->toAbsolute(new URL('g;x')));
        $this->assertEquals('http://a/b/c/g;x?y#s', $base->toAbsolute(new URL('g;x?y#s')));
        $this->assertEquals('http://a/b/c/d;p?q', $base->toAbsolute(new URL('')));
        $this->assertEquals('http://a/b/c/', $base->toAbsolute(new URL('.')));
        $this->assertEquals('http://a/b/c/', $base->toAbsolute(new URL('./')));
        $this->assertEquals('http://a/b/', $base->toAbsolute(new URL('..')));
        $this->assertEquals('http://a/b/', $base->toAbsolute(new URL('../')));
        $this->assertEquals('http://a/b/g', $base->toAbsolute(new URL('../g')));
        $this->assertEquals('http://a/', $base->toAbsolute(new URL('../..')));
        $this->assertEquals('http://a/', $base->toAbsolute(new URL('../../')));
        $this->assertEquals('http://a/g', $base->toAbsolute(new URL('../../g')));
        $this->assertEquals('http://a/g', $base->toAbsolute(new URL('../../../g')));
        $this->assertEquals('http://a/g', $base->toAbsolute(new URL('/./g')));
        $this->assertEquals('http://a/g', $base->toAbsolute(new URL('/../g')));
        $this->assertEquals('http://a/b/c/g.', $base->toAbsolute(new URL('g.')));
        $this->assertEquals('http://a/b/c/.g', $base->toAbsolute(new URL('.g')));
        $this->assertEquals('http://a/b/c/g..', $base->toAbsolute(new URL('g..')));
        $this->assertEquals('http://a/b/c/..g', $base->toAbsolute(new URL('..g')));
        $this->assertEquals('http://a/b/g', $base->toAbsolute(new URL('./../g')));
        $this->assertEquals('http://a/b/c/g/', $base->toAbsolute(new URL('./g/.')));
        $this->assertEquals('http://a/b/c/g/h', $base->toAbsolute(new URL('g/./h')));
        $this->assertEquals('http://a/b/c/h', $base->toAbsolute(new URL('g/../h')));
        $this->assertEquals('http://a/b/c/g;x=1/y', $base->toAbsolute(new URL('g;x=1/./y')));
        $this->assertEquals('http://a/b/c/y', $base->toAbsolute(new URL('g;x=1/../y')));
    }

    /**
     * @covers pedroac\url\Base::getURL
     * @covers pedroac\url\Base::fromString
     */
    public function testGetURL()
    {
        $base = Base::fromString('http://a/b/c/d;p?q');
        $this->assertEquals('http://a/b/c/d;p?q', $base->getURL());
    }
}
