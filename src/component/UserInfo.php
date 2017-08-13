<?php
/**
 * UserInfo class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url\component;

use pedroac\url\AccessorTrait;

/**
 * UserInfo URI component
 * It should be immutable.
 * @property-read string|null $username
 * @property-read string|null $password
 */
class UserInfo
{
    use AccessorTrait;

    /**
     * Username.
     * @var string|null
     */
    private $username;

    /**
     * Password.
     * @var string|null
     */
    private $password;

    /****************
     * Constructions
     */

    /**
     * Create a new UserInfo URI component value object.
     * If the first argument is NULL, the URI should not have a username.
     * If the second argument is NULL, the URI should not have a password.
     * Unencrypted passwords are deprecated according to the RFC 3986.
     * @param string|null $username Username.
     * @param string|null $password Password.
     */
    public function __construct(
        string $username = null,
        string $password = null
    ) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Create a new object from a string.
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.1
     * @param string|null $string URI component following the format
     *                            "[username[:format]]"
     * @return self
     */
    public static function fromString(string $string = null): self
    {
        if ($string === null) {
            return new self(null);
        }
        $parts = explode(':', $string);
        return new self($parts[0], $parts[1]??null);
    }

    /**************
     * Conversions
     */

    /**
     * Build the URI component string without the "@" delimiter.
     * If the password is NULL, then the ":" delimiter should not be included.
     * Otherwise, the ":" should be included.
     * @see self::fromString
     * @return string The userinfo string representation.
     */
    public function __toString(): string
    {
        if ($this->username === null) {
            return '';
        }

        if ($this->password === null) {
            return urlencode(urldecode($this->username));
        }

        return urlencode(urldecode($this->username))
               . ':'
               . urlencode(urldecode($this->password));
    }

    /**
     * Build the URI component string representation.
     * If the username is NULL, an empty string should be returned.
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.1
     * @return string URI component with the "@" delimiter or an empty string.
     */
    public function toURIString(): string
    {
        return ($this->username === null) ? '' : "{$this}@";
    }

    /**********
     * Getters
     */

    /**
     * Check if the URI component is empty.
     * It's empty if the username is empty.
     * @return bool Is it empty?
     */
    public function isEmpty(): bool
    {
        return !isset($this->username[0]);
    }

    /**
     * Return the username.
     * @return string|null Username.
     * @todo Add the ?string type hint when it's available to phpDoc.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Return the password.
     * @return string|null Password.
     * @todo Add the ?string type hint when it's available to phpDoc.
     */
    public function getPassword()
    {
        return $this->password;
    }
}
