<?php

namespace pedroac\url;

use PHPUnit\Framework\TestCase;
use pedroac\url\component\Authority;
use pedroac\url\component\Scheme;
use pedroac\url\component\Host;
use pedroac\url\component\UserInfo;
use pedroac\url\component\Path;
use pedroac\url\component\Query;
use pedroac\url\component\Fragment;
use pedroac\url\component\Port;

class ComponentsTest extends TestCase
{

    /**
     * @covers pedroac\url\Components::setScheme
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__set
     */
    public function testSetScheme()
    {
        $components = new Components();

        $components->scheme = new Scheme('http');
        $this->assertEquals('http:', $components);

        $components->scheme = new Scheme('https');
        $this->assertEquals('https:', $components);

        $components->scheme = new Scheme('');
        $this->assertEquals('', $components);
    }

    /**
     * @covers pedroac\url\Components::setAuthority
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__set
     */
    public function testSetAuthority()
    {
        $components = new Components();

        $components->authority = Authority::fromString('pedroac:qwerty@test.com:80');
        $this->assertEquals('//pedroac:qwerty@test.com:80', $components);

        $components->authority = Authority::fromString('pedroac:@test.com:80');
        $this->assertEquals('//pedroac:@test.com:80', $components);

        $components->authority = Authority::fromString('pedroac@test.com:80');
        $this->assertEquals('//pedroac@test.com:80', $components);

        $components->authority = Authority::fromString(':@test.com:80');
        $this->assertEquals('//:@test.com:80', $components);

        $components->authority = Authority::fromString('@test.com:80');
        $this->assertEquals('//@test.com:80', $components);

        $components->authority = Authority::fromString('test.com:80');
        $this->assertEquals('//test.com:80', $components);

        $components->authority = Authority::fromString('test.com');
        $this->assertEquals('//test.com', $components);

        $components->authority = Authority::fromString(':80');
        $this->assertEquals('', $components);

        $components->authority = Authority::fromString('pedroac:qwerty@');
        $this->assertEquals('', $components);

        $components->authority = Authority::fromString(':qwerty@');
        $this->assertEquals('', $components);

        $components->authority = Authority::fromString('');
        $this->assertEquals('', $components);
    }

    /**
     * @covers pedroac\url\Components::setUserInfo
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__set
     */
    public function testSetUserInfo()
    {
        $components = new Components();

        $components->userInfo = UserInfo::fromString('pedro:qwerty');
        $this->assertEquals('', $components);

        $components->userInfo = UserInfo::fromString('');
        $this->assertEquals('', $components);

        $components->authority = Authority::fromString('test.com:80');
        $components->userInfo = UserInfo::fromString('pedro:qwerty');
        $this->assertEquals('//pedro:qwerty@test.com:80', $components);

        $components->authority = Authority::fromString('test.com');
        $components->userInfo = UserInfo::fromString('pedro:qwerty');
        $this->assertEquals('//pedro:qwerty@test.com', $components);
    }

    /**
     * @covers pedroac\url\Components::setHost
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__set
     */
    public function testSetHost()
    {
        $components = new Components();

        $components->host = new Host('test.com');
        $this->assertEquals('//test.com', $components);

        $components->host = new Host();
        $this->assertEquals('', $components);
    }

    /**
     * @covers pedroac\url\Components::setPort
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__set
     */
    public function testSetPort()
    {
        $components = new Components();

        $components->port = new Port(80);
        $this->assertEquals('', $components);

        $components->port = new Port;
        $this->assertEquals('', $components);
    }

    /**
     * @covers pedroac\url\Components::setPath
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__set
     */
    public function testSetPath()
    {
        $components = new Components();

        $components->path = new Path('my/path');
        $this->assertEquals('my/path', $components);

        $components->path = new Path('/my/path');
        $this->assertEquals('/my/path', $components);

        $components->path = new Path('/');
        $this->assertEquals('/', $components);

        $components->path = new Path('');
        $this->assertEquals('', $components);
    }

    /**
     * @covers pedroac\url\Components::setQuery
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__set
     */
    public function testSetQuery()
    {
        $components = new Components();

        $components->query = Query::fromArray(['a' => 1, 'b' => 2]);
        $this->assertEquals('?a=1&b=2', $components);

        $components->query = new Query('');
        $this->assertEquals('?', $components);

        $components->query = new Query(null);
        $this->assertEquals('', $components);
    }

    /**
     * @covers pedroac\url\Components::setFragment
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__set
     */
    public function testSetFragment()
    {
        $components = new Components();

        $components->fragment = new Fragment('label');
        $this->assertEquals('#label', $components);

        $components->fragment = new Fragment('');
        $this->assertEquals('#', (string)$components);

        $components->fragment = new Fragment(null);
        $this->assertEquals('', (string)$components);
    }

    /**
     * @covers pedroac\url\Components::getScheme
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__get
     */
    public function testGetScheme()
    {
        $components = new Components(new Scheme('http'));
        $this->assertEquals('http', $components->getScheme());
    }

    /**
     * @covers pedroac\url\Components::getHost
     * @covers pedroac\url\Components::__toString
     * @covers pedroac\url\Components::__get
     */
    public function testGetHost()
    {
        $components = new Components(null, Authority::fromString('test.com'));
        $this->assertEquals('test.com', $components->getHost());
    }

    /**
     * @covers pedroac\url\Components::getPort
     */
    public function testGetPort()
    {
        $components = new Components(null, Authority::fromString('test.com:80'));
        $this->assertEquals('80', $components->getPort());
    }

    /**
     * @covers pedroac\url\Components::getUserInfo
     * @covers pedroac\url\Components::__get
     */
    public function testGetUserInfo()
    {
        $components = new Components(null, Authority::fromString('pedro:qwerty@test.com:80'));
        $this->assertEquals('pedro:qwerty', $components->getUserInfo());
    }

    /**
     * @covers pedroac\url\Components::getAuthority
     * @covers pedroac\url\Components::__get
     */
    public function testGetAuthority()
    {
        $components = new Components(null, Authority::fromString('pedro:qwerty@test.com:80'));
        $this->assertEquals('pedro:qwerty@test.com:80', $components->getAuthority());
    }

    /**
     * @covers pedroac\url\Components::getPath
     * @covers pedroac\url\Components::__get
     */
    public function testGetPath()
    {
        $components = new Components(null, null, new Path('my/path'));
        $this->assertEquals('my/path', $components->getPath());
    }

    /**
     * @covers pedroac\url\Components::getQuery
     * @covers pedroac\url\Components::__get
     */
    public function testGetQuery()
    {
        $components = new Components(null, null, null, new Query('a=1&b=2'));
        $this->assertEquals('a=1&b=2', $components->getQuery());
    }

    /**
     * @covers pedroac\url\Components::getFragment
     * @covers pedroac\url\Components::__get
     */
    public function testGetFragment()
    {
        $components = new Components(null, null, null, null, new Fragment('label'));
        $this->assertEquals('label', $components->getFragment());
    }

    /**
     * @covers pedroac\url\Components::__construct
     * @covers pedroac\url\Components::__toString
     */
    public function testToString()
    {
        $this->assertEquals('https://pedro:qwerty@test.com:80/hello/world?a=1&b=2#label',
                            (string)new Components(new Scheme('https'),
                                                   Authority::fromString('pedro:qwerty@test.com:80'),
                                                   new Path('hello/world'),
                                                   new Query('a=1&b=2'),
                                                   new Fragment('label')));
        $this->assertEquals('https://pedro:qwerty@test.com:80/hello/world?a=1&b=2',
                            (string)new Components(new Scheme('https'),
                                                   Authority::fromString('pedro:qwerty@test.com:80'),
                                                   new Path('hello/world'),
                                                   new Query('a=1&b=2')));
        $this->assertEquals('https://pedro:qwerty@test.com:80/hello/world',
                            (string)new Components(new Scheme('https'),
                                                   Authority::fromString('pedro:qwerty@test.com:80'),
                                                   new Path('hello/world')));
        $this->assertEquals('https://pedro:qwerty@test.com:80',
                            (string)new Components(new Scheme('https'),
                                                   Authority::fromString('pedro:qwerty@test.com:80')));
        $this->assertEquals('https:',
                            (string)new Components(new Scheme('https')));
        $this->assertEquals('https://test.com:80',
                            (string)new Components(new Scheme('https'),
                                                   Authority::fromString('test.com:80')));
        $this->assertEquals('https://test.com',
                            (string)new Components(new Scheme('https'),
                                                   Authority::fromString('test.com')));
        $this->assertEquals('https:',
                            (string)new Components(new Scheme('https'),
                                                   Authority::fromString(':80')));
        $this->assertEquals('mail:test@host.com',
                            (string)new Components(new Scheme('mail'),
                                                   null,
                                                   new Path('test@host.com')));
        $this->assertEquals('mail:test@host.com?a=1&b=2',
                            (string)new Components(new Scheme('mail'),
                                                   null,
                                                   new Path('test@host.com'),
                                                   new Query('a=1&b=2')));
    }

    /**
     * @covers pedroac\url\Components::replaceWith
     * @todo Test unicode usecases.
     */
    public function testReplaceWith()
    {
        $components = new Components(new Scheme('https'),
                                     Authority::fromString('pedro:qwerty@test.com:80'),
                                     new Path('hello/world'),
                                     new Query('a=1&b=2'),
                                     new Fragment('label'));
        $components1 = new Components(new Scheme('http'),
                                     Authority::fromString('test.com'),
                                     new Path('hello/another/world'),
                                     new Query('c=30'),
                                     new Fragment('newlabel'));
        $components2 = new Components(new Scheme('http'),
                                      Authority::fromString('test.com'),
                                      new Path('hello/another/world'),
                                      new Query(null),
                                      new Fragment(null));

        $components->replaceWith($components1);
        $this->assertEquals('http://test.com/hello/another/world?c=30#newlabel',
                            (string)$components);

        $components->replaceWith($components2);
        $this->assertEquals('http://test.com/hello/another/world',
                            (string)$components);

        // Check if the replacements weren't changed.
        $this->assertEquals('http://test.com/hello/another/world?c=30#newlabel',
                            (string)$components1);
        $this->assertEquals('http://test.com/hello/another/world',
                            (string)$components2);
    }
}
