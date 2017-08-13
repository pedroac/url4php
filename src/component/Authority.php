<?php
/**
 * Authority class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.3.1
 */
namespace pedroac\url\component;

use pedroac\url\AccessorTrait;

/**
 * Authority URI component, with host, port and userinfo components.
 * It should be immutable.
 * @property-read UserInfo $userInfo
 * @property-read Host     $host
 * @property-read Port     $port
 */
class Authority
{
    use AccessorTrait;

    /**
     * @var UserInfo
     */
    private $userInfo;

    /**
     * @var Host
     */
    private $host;

    /**
     * @var Port
     */
    private $port;

    /**
     * Regex pattern to retrieve URI components.
     * @var string
     */
    const REGEX_AUTHORITY = '~^(?://)?'
                            . '([^@]*@)?'        // 1 UserInfo
                            . '([^:]*)'          // 2 Host
                            . '(?:[:](\d+))?~u'; // 3 Port

    /****************
     * Constructions
     */

    /**
     * Create a new authority URI component value object.
     * If the third argument ($userInfo) is NULL, then username and password will be NULL.
     * @param Host|null      $host
     * @param Port|null      $port
     * @param UserInfo|null  $userInfo
     */
    public function __construct(
        Host $host = null,
        Port $port = null,
        UserInfo $userInfo = null
    ) {
        $this->host     = $host ?? new Host;
        $this->port     = $port ?? new Port;
        $this->userInfo = $userInfo ?? new UserInfo;
    }

    /**
     * Create a new object from a string.
     * @param string $string Format: "[//][username[":"password]"@"]host[":"port]".
     * @return self
     */
    public static function fromString(string $string): self
    {
        $matches=array();
        preg_match(self::REGEX_AUTHORITY, $string, $matches);
        if (!isset($matches[2][0])) {
            return new self;
        }
        return new self(new Host($matches[2]),
                        new Port($matches[3] ?? null),
                        UserInfo::fromString(isset($matches[1][0])
                                             ? rtrim($matches[1], '@')
                                             : null));
    }

    /**************
     * Conversions
     */

    /**
     * Build the URI component string without the "//" delimiter.
     * If the username is NULL, then the userinfo component should not be included.
     * @return string The authorization string representation.
     */
    public function __toString(): string
    {
        if ($this->isEmpty()) {
            return '';
        }
        return   $this->userInfo->toUriString()
               . $this->host->toUriString()
               . $this->port->toUriString();
    }

    /**
     * Build the URI component string representation with the "//" delimiter.
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI component or an empty string.
     */
    public function toURIString(): string
    {
        return $this->isEmpty() ? '' : "//$this";
    }

    /**********
     * Getters
     */

    /**
     * Check if the URI component is empty.
     * It's empty if the host is empty.
     * @return bool Is the authority empty?
     */
    public function isEmpty(): bool
    {
        return $this->host->isEmpty();
    }

    /**
     * Return the host URI component.
     * @return Host
     */
    public function getHost(): Host
    {
        return $this->host;
    }

    /**
     * Return the port URI component.
     * @return Port
     */
    public function getPort(): Port
    {
        return $this->port;
    }

    /**
     * Return the UserInfo URI component.
     * @return UserInfo
     */
    public function getUserInfo(): UserInfo
    {
        return $this->userInfo;
    }

    /**
     * Return the username.
     * @return string|null Userinfo username.
     * @todo Add the ?string type hint when it's available to phpDoc.
     */
    public function getUsername()
    {
        return $this->userInfo->getUsername();
    }

    /**
     * Return the password.
     * @return string|null Userinfo password.
     * @todo Add the ?string type hint when it's available to phpDoc.
     */
    public function getPassword()
    {
        return $this->userInfo->getPassword();
    }
}
