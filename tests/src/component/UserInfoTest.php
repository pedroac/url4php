<?php

namespace pedroac\url\component;

use PHPUnit\Framework\TestCase;

class UserInfoTest extends TestCase
{
    /**
     * @covers pedroac\url\component\UserInfo::__construct
     * @covers pedroac\url\component\UserInfo::getUsername
     * @covers pedroac\url\component\UserInfo::getPassword
     * @covers pedroac\url\component\UserInfo::__get
     */
    public function testConstruct()
    {
        $this->assertSame('pedro', (new UserInfo('pedro', 'qwerty'))->username);
        $this->assertSame('qwerty', (new UserInfo('pedro', 'qwerty'))->password);
        $this->assertSame('pedro', (new UserInfo('pedro'))->username);
        $this->assertSame(null, (new UserInfo('pedro'))->password);
        $this->assertSame(null, (new UserInfo)->username);
        $this->assertSame(null, (new UserInfo)->password);
    }

    /**
     * @covers pedroac\url\component\UserInfo::fromString
     * @covers pedroac\url\component\UserInfo::getUsername
     * @covers pedroac\url\component\UserInfo::__get
     */
    public function testGetUsername()
    {
        $this->assertSame('pedro', (UserInfo::fromString('pedro:qwerty'))->username);
        $this->assertSame('pedro', (UserInfo::fromString('pedro:'))->username);
        $this->assertSame('pedro', (UserInfo::fromString('pedro'))->username);
        $this->assertSame('', (UserInfo::fromString(':'))->username);
        $this->assertSame('', (UserInfo::fromString(''))->username);
        $this->assertSame('', (UserInfo::fromString(':qwerty'))->username);
    }

    /**
     * @covers pedroac\url\component\UserInfo::fromString
     * @covers pedroac\url\component\UserInfo::getPassword
     * @covers pedroac\url\component\UserInfo::__get
     */
    public function testGetPassword()
    {
        $this->assertSame('qwerty', (UserInfo::fromString('pedro:qwerty'))->password);
        $this->assertSame('', (UserInfo::fromString('pedro:'))->password);
        $this->assertSame(null, (UserInfo::fromString('pedro'))->password);
        $this->assertSame('', (UserInfo::fromString(':'))->password);
        $this->assertSame(null, (UserInfo::fromString(''))->password);
        $this->assertSame('qwerty', (UserInfo::fromString(':qwerty'))->password);
    }

    /**
     * @covers pedroac\url\component\UserInfo::fromString
     * @covers pedroac\url\component\UserInfo::__toString
     */
    public function testToString()
    {
        $this->assertSame('', (string)UserInfo::fromString(null));
        $this->assertSame('pedro:qwerty', (string)UserInfo::fromString('pedro:qwerty'));
        $this->assertSame('pedro:', (string)UserInfo::fromString('pedro:'));
        $this->assertSame('pedro', (string)UserInfo::fromString('pedro'));
        $this->assertSame(':', (string)UserInfo::fromString(':'));
        $this->assertSame('', (string)UserInfo::fromString(''));
        $this->assertSame(':qwerty', (string)(UserInfo::fromString(':qwerty')));
    }

    /**
     * @covers pedroac\url\component\UserInfo::fromString
     * @covers pedroac\url\component\UserInfo::toURIString
     * @todo Test unicode edge cases.
     */
    public function testToURIString()
    {
        $this->assertSame('pedro:qwerty@', UserInfo::fromString('pedro:qwerty')->toURIString());
        $this->assertSame('pedro:@', UserInfo::fromString('pedro:')->toURIString());
        $this->assertSame('pedro@', UserInfo::fromString('pedro')->toURIString());
        $this->assertSame(':@', UserInfo::fromString(':')->toURIString());
        $this->assertSame('@', UserInfo::fromString('')->toURIString());
        $this->assertSame(':qwerty@', (UserInfo::fromString(':qwerty'))->toURIString());
    }

    /**
     * @covers pedroac\url\component\UserInfo::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertFalse( UserInfo::fromString('pedro:qwerty')->isEmpty() );
        $this->assertTrue( UserInfo::fromString('')->isEmpty() );
        $this->assertTrue( UserInfo::fromString(':qwerty')->isEmpty() );
        $this->assertTrue( UserInfo::fromString(':')->isEmpty() );
    }
}
