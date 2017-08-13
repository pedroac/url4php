<?php
namespace pedroac\url\component;

use PHPUnit\Framework\TestCase;

class AuthorityTest extends TestCase
{
    /**
     * @covers pedroac\url\component\Authority::__toString
     * @covers pedroac\url\component\Authority::__construct
     */
    public function testToString()
    {
        $this->assertEquals('myhost.com', 
                            (string)(new Authority(
                                        new Host('MyHost.com')
                                    )));
        $this->assertEquals('myhost.com:80', 
                            (string)(new Authority(
                                        new Host('myhost.com'),
                                        new Port(80)
                                    )));
        $this->assertEquals('pedro:qwerty@myhost.com:80', 
                            (string)(new Authority(
                                        new Host('myhost.com'),
                                        new Port(80),
                                        new UserInfo('pedro', 'qwerty')
                                    )));
    }

    /**
     * @covers pedroac\url\component\Authority::fromString
     * @covers pedroac\url\component\Authority::getHost
     */
    public function testGetHost()
    {
        $this->assertEquals('myhost.com', (Authority::fromString('pedro:qwerty@myhost.com:80'))->host);
        $this->assertEquals('myhost.com', (Authority::fromString('pedro:@myhost.com:80'))->host);
        $this->assertEquals('myhost.com', (Authority::fromString(':@myhost.com:80'))->host);
        $this->assertEquals('myhost.com', (Authority::fromString('@myhost.com:80'))->host);
        $this->assertEquals('myhost.com', (Authority::fromString('@myhost.com'))->host);
        $this->assertEquals('', (Authority::fromString(':80'))->host);
        $this->assertEquals('', (Authority::fromString('pedroac:qwerty@'))->host);
        $this->assertEquals('', (Authority::fromString('pedroac:@'))->host);
        $this->assertEquals('', (Authority::fromString(':@'))->host);
        $this->assertEquals('', (Authority::fromString(''))->host);
    }

    /**
     * @covers pedroac\url\component\Authority::fromString
     * @covers pedroac\url\component\Authority::getPort
     */
    public function testGetPort()
    {
        $this->assertEquals('80', (Authority::fromString('pedro:qwerty@myhost.com:80'))->port);
        $this->assertEquals('80', (Authority::fromString('pedro:@myhost.com:80'))->port);
        $this->assertEquals('80', (Authority::fromString(':@myhost.com:80'))->port);
        $this->assertEquals('80', (Authority::fromString('@myhost.com:80'))->port);
        $this->assertEquals('', (Authority::fromString('@myhost.com'))->port);
        $this->assertEquals('', (Authority::fromString(':80'))->port);
        $this->assertEquals('', (Authority::fromString('pedroac:qwerty@'))->port);
        $this->assertEquals('', (Authority::fromString('pedroac:@'))->port);
        $this->assertEquals('', (Authority::fromString(':@'))->port);
        $this->assertEquals('', (Authority::fromString(''))->port);
    }

    /**
     * @covers pedroac\url\component\Authority::fromString
     * @covers pedroac\url\component\Authority::getUserInfo
     */
    public function testGetUserInfo()
    {
        $this->assertEquals('pedro:qwerty', (Authority::fromString('pedro:qwerty@myhost.com:80'))->userInfo);
        $this->assertEquals('pedro:', (Authority::fromString('pedro:@myhost.com:80'))->userInfo);
        $this->assertEquals(':', (Authority::fromString(':@myhost.com:80'))->userInfo);
        $this->assertEquals('', (Authority::fromString('@myhost.com:80'))->userInfo);
        $this->assertEquals('', (Authority::fromString('@myhost.com'))->userInfo);
        $this->assertEquals('', (Authority::fromString(':80'))->userInfo);
        $this->assertEquals('', (Authority::fromString('pedroac:qwerty@'))->userInfo);
        $this->assertEquals('', (Authority::fromString('pedroac:@'))->userInfo);
        $this->assertEquals('', (Authority::fromString(':@'))->userInfo);
        $this->assertEquals('', (Authority::fromString(''))->userInfo);
    }

    /**
     * @covers pedroac\url\component\Authority::fromString
     * @covers pedroac\url\component\Authority::getUsername
     */
    public function testGetUsername()
    {
        $this->assertSame('pedro', (Authority::fromString('pedro:qwerty@myhost.com:80'))->username);
        $this->assertSame('pedro', (Authority::fromString('pedro:@myhost.com:80'))->username);
        $this->assertSame('', (Authority::fromString(':@myhost.com:80'))->username);
        $this->assertSame('', (Authority::fromString('@myhost.com:80'))->username);
        $this->assertSame('', (Authority::fromString('@myhost.com'))->username);
        $this->assertSame(null, (Authority::fromString(':80'))->username);
        $this->assertSame(null, (Authority::fromString('pedroac:qwerty@'))->username);
        $this->assertSame(null, (Authority::fromString('pedroac:@'))->username);
        $this->assertSame(null, (Authority::fromString(':@'))->username);
        $this->assertSame(null, (Authority::fromString(''))->username);
    }

    /**
     * @covers pedroac\url\component\Authority::fromString
     * @covers pedroac\url\component\Authority::getPassword
     */
    public function testGetPassword()
    {
        $this->assertSame('qwerty', (Authority::fromString('pedro:qwerty@myhost.com:80'))->password);
        $this->assertSame('', (Authority::fromString('pedro:@myhost.com:80'))->password);
        $this->assertSame('', (Authority::fromString(':@myhost.com:80'))->password);
        $this->assertSame(null, (Authority::fromString('@myhost.com:80'))->password);
        $this->assertSame(null, (Authority::fromString('@myhost.com'))->password);
        $this->assertSame(null, (Authority::fromString(':80'))->password);
        $this->assertSame(null, (Authority::fromString('pedroac:qwerty@'))->password);
        $this->assertSame(null, (Authority::fromString('pedroac:@'))->password);
        $this->assertSame(null, (Authority::fromString(':@'))->password);
        $this->assertSame(null, (Authority::fromString(''))->password);
    }

    /**
     * @covers pedroac\url\component\Authority::fromString
     * @covers pedroac\url\component\Authority::__toString
     */
    public function testFromString()
    {
        $this->assertEquals('pedro:qwerty@myhost.com:80', (string)Authority::fromString('pedro:qwerty@myhost.com:80'));
        $this->assertEquals('pedro:@myhost.com:80', (string)Authority::fromString('pedro:@myhost.com:80'));
        $this->assertEquals(':@myhost.com:80', (string)Authority::fromString(':@myhost.com:80'));
        $this->assertEquals('@myhost.com:80', (string)Authority::fromString('@myhost.com:80'));
        $this->assertEquals('myhost.com:80', (string)Authority::fromString('myhost.com:80'));
        $this->assertEquals('myhost.com', (string)Authority::fromString('myhost.com'));
        $this->assertEquals('', (string)Authority::fromString(':80'));
        $this->assertEquals('', (string)Authority::fromString('pedro:qwerty@'));
        $this->assertEquals('', (string)Authority::fromString('pedro:@'));
        $this->assertEquals('', (string)Authority::fromString(':@'));
        $this->assertEquals('', (string)Authority::fromString(':'));
        $this->assertEquals('', (string)Authority::fromString(''));
    }

    /**
     * @covers pedroac\url\component\Authority::fromString
     * @covers pedroac\url\component\Authority::toURIString
     * @todo Test unicode edge cases.
     */
    public function testToURIString()
    {
        $this->assertEquals('//pedro:qwerty@myhost.com:80',
                            Authority::fromString('pedro:qwerty@myhost.com:80')->toURIString());
        $this->assertEquals('//pedro:@myhost.com:80', Authority::fromString('pedro:@myhost.com:80')->toURIString());
        $this->assertEquals('//:@myhost.com:80', Authority::fromString(':@myhost.com:80')->toURIString());
        $this->assertEquals('//@myhost.com:80', Authority::fromString('@myhost.com:80')->toURIString());
        $this->assertEquals('//myhost.com:80', Authority::fromString('myhost.com:80')->toURIString());
        $this->assertEquals('//myhost.com', Authority::fromString('myhost.com')->toURIString());
        $this->assertEquals('', Authority::fromString(':80')->toURIString());
        $this->assertEquals('', Authority::fromString('pedro:qwerty@')->toURIString());
        $this->assertEquals('', Authority::fromString('pedro:@')->toURIString());
        $this->assertEquals('', Authority::fromString(':@')->toURIString());
        $this->assertEquals('', Authority::fromString(':')->toURIString());
        $this->assertEquals('', Authority::fromString('')->toURIString());
    }

    /**
     * @covers pedroac\url\component\Authority::fromString
     * @covers pedroac\url\component\Authority::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertFalse((Authority::fromString('pedro:qwerty@myhost.com:80'))->isEmpty());
        $this->assertFalse((Authority::fromString('pedro:@myhost.com:80'))->isEmpty());
        $this->assertFalse((Authority::fromString(':@myhost.com:80'))->isEmpty());
        $this->assertFalse((Authority::fromString('myhost.com:80'))->isEmpty());
        $this->assertFalse((Authority::fromString('myhost.com'))->isEmpty());
        $this->assertTrue((Authority::fromString(':80'))->isEmpty());
        $this->assertTrue((Authority::fromString('pedro:qwerty@'))->isEmpty());
        $this->assertTrue((Authority::fromString('pedro:@'))->isEmpty());
        $this->assertTrue((Authority::fromString(':@'))->isEmpty());
        $this->assertTrue((Authority::fromString(':'))->isEmpty());
        $this->assertTrue((Authority::fromString(''))->isEmpty());
    }
}
