<?php

namespace pedroac\url;

use PHPUnit\Framework\TestCase;
use pedroac\url\component\Scheme;
use pedroac\url\component\Authority;
use pedroac\url\component\Path;
use pedroac\url\component\Query;
use pedroac\url\component\Fragment;

class URLTest extends TestCase
{
    /**
     * @covers pedroac\url\Url::__construct
     * @covers pedroac\url\Url::__toString
     */
    public function test__toString()
    {
        $this->assertEquals('http://test.com', new URL('http://test.com'));
    }

    /**
     * @covers pedroac\url\URL::parse
     */
    public function testParse()
    {
        $parsed = (new URL('https://pac.pt:80/my/path?a=1&b=2#label'))
                   ->parse();

        $this->assertEquals('https', $parsed->getScheme());
        $this->assertEquals('pac.pt:80', $parsed->getAuthority());
        $this->assertEquals('pac.pt', $parsed->getHost());
        $this->assertEquals('80', $parsed->getPort());
        $this->assertEquals('/my/path', $parsed->getPath());
        $this->assertEquals('label', $parsed->getFragment());

        $this->assertEquals('https://pac.pt:80/my/path?a=1&b=2#label',
                            (new URL('https://pac.pt:80/my/path?a=1&b=2#label'))
                             ->parse());
    }

    /**
     * @covers pedroac\url\URL::fromServerVars
     */
    public function testFromServerVars()
    {
        $this->assertEquals('https://pac.pt/my/path',
                            URL::fromServerVars([
                                'SERVER_NAME' => 'pac.pt',
                                'HTTPS'       => 'on',
                                'SERVER_PORT' => 443,
                                'REQUEST_URI' => '/my/path'
                            ]));
        $this->assertEquals('http://pac.pt/my/path',
                            URL::fromServerVars([
                                'SERVER_NAME' => 'pac.pt',
                                'HTTPS'       => 'off',
                                'SERVER_PORT' => 80,
                                'REQUEST_URI' => '/my/path'
                            ]));
        $this->assertEquals('http://pac.pt:443/my/path',
                            URL::fromServerVars([
                                'SERVER_NAME' => 'pac.pt',
                                'HTTPS'       => 'off',
                                'SERVER_PORT' => 443,
                                'REQUEST_URI' => '/my/path'
                            ]));
        $this->assertEquals('http://pac.pt:300/my/path',
                            URL::fromServerVars([
                                'SERVER_NAME' => 'pac.pt',
                                'HTTPS'       => 'off',
                                'SERVER_PORT' => 300,
                                'REQUEST_URI' => '/my/path'
                            ]));
        $this->assertEquals('http://pac.pt/my/path',
                            URL::fromServerVars([
                                'SERVER_NAME' => 'pac.pt',
                                'HTTPS'       => 'on',
                                'REQUEST_SCHEME' => 'http',
                                'SERVER_PORT' => 80,
                                'REQUEST_URI' => '/my/path'
                            ]));
        $this->assertEquals('http://pac.pt/my/path?a=1&b=2',
                            URL::fromServerVars([
                                'SERVER_NAME' => 'pac.pt',
                                'HTTPS'       => 'on',
                                'REQUEST_SCHEME' => 'http',
                                'SERVER_PORT' => 80,
                                'REQUEST_URI' => '/my/path',
                                'QUERY_STRING' => 'a=1&b=2'
                            ]));
        
        $this->expectException(\InvalidArgumentException::class);
        URL::fromServerVars([]);
    }

    /**
     * @covers pedroac\url\URL::with
     */
    public function testWith()
    {
        $this->assertEquals('https://host.com',
                            (new URL('http://host.com'))
                             ->with(new Scheme('https')));

        $this->assertEquals('http://pac.pt',
                            (new URL('http://host.com'))
                             ->with(null, Authority::fromString('pac.pt')));
        $this->assertEquals('http://host.com/my/path',
                            (new URL('http://host.com'))
                             ->with(null, null, new Path('my/path')));

        $this->assertEquals('http://host.com/my/path',
                            (new URL('http://host.com'))
                             ->with(null, null, new Path('/my/path')));

        $this->assertEquals('http://host.com?a=1&b=2',
                            (new URL('http://host.com'))
                             ->with(null, null, null, new Query('a=1&b=2')));

        $this->assertEquals('http://host.com#label',
                            (new URL('http://host.com'))
                             ->with(null, null, null, null, new Fragment('label')));

        $this->assertEquals('https://pac.pt:80/my/path?a=1&b=2#label',
                            (new URL('http://host.com'))
                             ->with(new Scheme('https'),
                                    Authority::fromString('pac.pt:80'),
                                    new Path('my/path'),
                                    new Query('a=1&b=2'),
                                    new Fragment('label')));
    }

    /**
     * @covers pedroac\url\URL::changeParameters
     */
    public function testChangeParameters()
    {
        $this->assertEquals('?a=1&b=2',
                            (new URL(''))
                             ->changeParameters(['a' => 1, 'b' => 2]));

        $this->assertEquals('?c=3&a=1&b=2',
                            (new URL('?c=3'))
                             ->changeParameters(['a' => 1, 'b' => 2]));

        $this->assertEquals('?a=1&b=2#label',
                            (new URL('#label'))
                             ->changeParameters(['a' => 1, 'b' => 2]));

        $this->assertEquals('my/path?a=1&b=2',
                            (new URL('my/path'))
                             ->changeParameters(['a' => 1, 'b' => 2]));

        $this->assertEquals('my/path?a=1&b=2#label',
                            (new URL('my/path#label'))
                             ->changeParameters(['a' => 1, 'b' => 2]));

        $this->assertEquals('http://host.com/my/path?a=1&b=2#label',
                            (new URL('http://host.com/my/path#label'))
                             ->changeParameters(['a' => 1, 'b' => 2]));
    }

    /**
     * @covers pedroac\url\URL::withPath
     */
    public function testWithPath()
    {
        $this->assertEquals('http://host.com',
                             (new URL('http://host.com/other/place'))
                              ->withPath(new Path()));

        $this->assertEquals('', (new URL(''))->withPath(new Path()));

        $this->assertEquals('http://host.com',
                            (new URL(new Path('http://host.com')))
                             ->withPath(new Path('')));

        $this->assertEquals('/my/path',
                            (new URL(''))->withPath(new Path('/my/path')));
        $this->assertEquals('my/path',
                            (new URL(''))->withPath(new Path('my/path')));

        $this->assertEquals('http://host.com/my/path',
                            (new URL('http://host.com'))
                             ->withPath(new Path('/my/path')));

        $this->assertEquals('http://host.com/my/path',
                            (new URL('http://host.com'))
                             ->withPath(new Path('my/path')));

        $this->assertEquals('http://host.com/my/path?a=1&b=2#label',
                            (new URL('http://host.com?a=1&b=2#label'))
                             ->withPath(new Path('/my/path')));

        $this->assertEquals('/my/path?a=1&b=2#label',
                            (new URL('?a=1&b=2#label'))
                             ->withPath(new Path('/my/path')));

        $this->assertEquals('/my/path?a=1&b=2',
                            (new URL('?a=1&b=2'))
                             ->withPath(new Path('/my/path')));

        $this->assertEquals('/my/path#label',
                            (new URL('#label'))
                             ->withPath(new Path('/my/path')));

        $this->assertEquals('/my/path#label',
                            (new URL('/other/place#label'))
                             ->withPath(new Path('/my/path')));

        $this->assertEquals('http://host.com/my/path?a=1&b=2#label',
                            (new URL('http://host.com?a=1&b=2#label'))
                             ->withPath(new Path('/my/path')));
    }

    /**
     * @covers pedroac\url\URL::withFragment
     */
    public function testWithFragment()
    {
        $this->assertEquals('http://site.com',
                            (new URL('http://site.com#label'))
                             ->withFragment(new Fragment(null)));

        $this->assertEquals('http://site.com?a=1&b=2',
                            (new URL('http://site.com?a=1&b=2#label'))
                             ->withFragment(new Fragment(null)));

        $this->assertEquals('#label',
                            (new URL(''))
                             ->withFragment(new Fragment('label')));

        $this->assertEquals('#label',
                            (new URL('#old'))
                             ->withFragment(new Fragment('label')));

        $this->assertEquals('?a=1&b=2#label',
                            (new URL('?a=1&b=2'))
                             ->withFragment(new Fragment('label')));

        $this->assertEquals('my/path#label',
                            (new URL('my/path'))
                             ->withFragment(new Fragment('label')));

        $this->assertEquals('http://site.com#label',
                            (new URL('http://site.com'))
                             ->withFragment(new Fragment('label')));

        $this->assertEquals('http://site.com?a=1&b=2#label',
                            (new URL('http://site.com?a=1&b=2'))
                             ->withFragment(new Fragment('label')));

        $this->assertEquals('http://site.com?a=1&b=2#label',
                            (new URL('http://site.com?a=1&b=2#old'))
                             ->withFragment(new Fragment('label')));
    }

    /**
     * @covers pedroac\url\URL::isAbsolute
     */
    public function testIsAbsolute()
    {
        $this->assertTrue((new URL('http://test.com'))->isAbsolute());

        $this->assertTrue((new URL('http://test.com/this/path'))->isAbsolute());
        $this->assertTrue((new URL('http://test.com/this/path?a=1&b=2'))->isAbsolute());
        $this->assertTrue((new URL('http://test.com#label'))->isAbsolute());
        $this->assertTrue((new URL('http://test.com/this/path?a=1&b=2#label'))->isAbsolute());
        $this->assertTrue((new URL('http://test.com/?a=1&b=2#label'))->isAbsolute());
        $this->assertTrue((new URL('http://test.com/#label'))->isAbsolute());

        $this->assertFalse((new URL('//test.com'))->isAbsolute());
        $this->assertFalse((new URL('http:'))->isAbsolute());
        $this->assertFalse((new URL('/this/path'))->isAbsolute());
        $this->assertFalse((new URL('this/path'))->isAbsolute());
        $this->assertFalse((new URL('?a=1&b=2'))->isAbsolute());
        $this->assertFalse((new URL('#label'))->isAbsolute());
        $this->assertFalse((new URL('?a=1&b=2#label'))->isAbsolute());
        $this->assertFalse((new URL('this/path?a=1&b2'))->isAbsolute());
        $this->assertFalse((new URL('this/path?a=1&b2#label'))->isAbsolute());
    }
}
