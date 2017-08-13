<?php

namespace pedroac\url;

use PHPUnit\Framework\TestCase;

class ParsedTest extends TestCase
{

    /**
     * @covers pedroac\url\Parsed::fromString
     * @covers pedroac\url\Parsed::__toString
     */
    public function testFromString()
    {
        $this->assertEquals('http://test.com',
                            (string)Parsed::fromString('http://test.com'));
    }

    /**
     * @covers pedroac\url\Parsed::toComponents
     * @covers pedroac\url\Parsed::__construct
     * @covers pedroac\url\Parsed::__toString
     */
    public function testToComponents()
    {
        $this->assertEquals('http://test.com',
                            (string)(new Parsed(new URL('http://test.com')))
                             ->toComponents());
    }

    /**
     * @covers pedroac\url\Parsed::getURL
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetUrl()
    {
        $this->assertEquals('http://test.com',
                            (new Parsed(new URL('http://test.com')))
                             ->getURL());
    }

    /**
     * @covers pedroac\url\Parsed::getScheme
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetScheme()
    {
        $this->assertEquals('http',
                            (new Parsed(new URL('http://test.com')))
                             ->getScheme());
        $this->assertEquals('http',
                            (new Parsed(new URL('http:')))
                             ->getScheme());
        $this->assertEquals('',
                            (new Parsed(new URL('//test.com')))
                             ->getScheme());
        $this->assertEquals('',
                            (new Parsed(new URL('')))
                             ->getScheme());
    }

    /**
     * @covers pedroac\url\Parsed::getHost
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetHost()
    {
        $this->assertEquals('test.com',
                            (new Parsed(new URL('http://test.com')))
                             ->getHost());
        $this->assertEquals('',
                            (new Parsed(new URL('http:')))
                             ->getHost());
        $this->assertEquals('',
                            (new Parsed(new URL('/my/path')))
                             ->getHost());
        $this->assertEquals('',
                            (new Parsed(new URL('')))
                             ->getHost());
    }

    /**
     * @covers pedroac\url\Parsed::getPort
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetPort()
    {
        $this->assertEquals('80',
                            (new Parsed(new URL('http://host.com:80')))
                             ->getPort());
        $this->assertEquals('80',
                            (new Parsed(new URL('//host.com:80')))
                             ->getPort());
        $this->assertEquals('',
                            (new Parsed(new URL('')))
                             ->getPort());
    }

    /**
     * @covers pedroac\url\Parsed::getUserInfo
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetUserInfo()
    {
        $this->assertEquals('pedro:qwerty',
                            (new Parsed(new URL('http://pedro:qwerty@host.com')))
                             ->getUserInfo());
        $this->assertEquals('pedro',
                            (new Parsed(new URL('http://pedro@host.com')))
                             ->getUserInfo());
        $this->assertEquals('',
                            (new Parsed(new URL('http://host.com')))
                             ->getUserInfo());
        $this->assertEquals('',
                            (new Parsed(new URL('')))
                             ->getUserInfo());
    }

    /**
     * @covers pedroac\url\Parsed::getAuthority
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetAuthority()
    {
        $this->assertEquals('pedro:qwerty@host.com:80',
                            (new Parsed(new URL('http://pedro:qwerty@host.com:80')))
                             ->getAuthority());
        $this->assertEquals('host.com:80',
                            (new Parsed(new URL('http://host.com:80')))
                             ->getAuthority());
        $this->assertEquals('host.com',
                            (new Parsed(new URL('http://host.com')))
                             ->getAuthority());
        $this->assertEquals('',
                            (new Parsed(new URL('http:')))
                             ->getAuthority());
        $this->assertEquals('',
                            (new Parsed(new URL('/my/path')))
                             ->getAuthority());
        $this->assertEquals('',
                            (new Parsed(new URL('mailto:pac@test.com')))
                             ->getAuthority());
        $this->assertEquals('',
                            (new Parsed(new URL('')))
                             ->getAuthority());
    }

    /**
     * @covers pedroac\url\Parsed::getPath
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetPath()
    {
        $this->assertEquals('/my/path',
                            (new Parsed(new URL('http://host.com/my/path')))
                             ->getPath());
        $this->assertEquals('',
                            (new Parsed(new URL('http://host.com')))
                             ->getPath());
        $this->assertEquals('my/path',
                            (new Parsed(new URL('my/path?a=1&b=2')))
                             ->getPath());
        $this->assertEquals('pac@test.com',
                            (new Parsed(new URL('mailto:pac@test.com')))
                             ->getPath());
        $this->assertEquals('',
                            (new Parsed(new URL('')))
                             ->getPath());
    }

    /**
     * @covers pedroac\url\Parsed::getQuery
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetQuery()
    {
        $this->assertEquals('a=1&b=2',
                            (new Parsed(new URL('http://host.com/my/path?a=1&b=2')))
                             ->getQuery());
        $this->assertEquals('',
                            (new Parsed(new URL('http://host.com/my/path')))
                             ->getQuery());
        $this->assertEquals('',
                            (new Parsed(new URL('')))
                             ->getQuery());
    }

    /**
     * @covers pedroac\url\Parsed::getFragment
     * @covers pedroac\url\Parsed::__construct
     */
    public function testGetFragment()
    {
        $this->assertEquals('label',
                            (new Parsed(new URL('http://host.com/my/path?a=1&b=2#label')))
                             ->getFragment());
        $this->assertEquals('label',
                            (new Parsed(new URL('http://host.com/my/path#label')))
                             ->getFragment());
        $this->assertEquals('label',
                            (new Parsed(new URL('http://host.com#label')))
                             ->getFragment());
        $this->assertEquals('label',
                            (new Parsed(new URL('#label')))
                             ->getFragment());
        $this->assertEquals('',
                            (new Parsed(new URL('')))
                             ->getFragment());
    }
}
