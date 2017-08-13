<?php
/**
 * Host class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url\component;

use TrueBV\Punycode;

/**
 * Host URI component.
 * It should be immutable.
 * It's assumed that it might have UTF-8 encoding characters.
 */
class Host
{
    /**
     * Host: a hostname or an IP address.
     * @var string
     */
    private $value;

    /**
     * Type: TYPE_EMPTY, TYPE_NAME, TYPE_IPV4 or TYPE_IPV6.
     * It's NULL until self::getType is called.
     * @var int
     */
    private $type = null;

    /**
     * Host types
     */
    /**
     * It's an empty or undefined host.
     * @var int
     */
    const TYPE_EMPTY = 0;
    /**
     * It's an hostname.
     * @var int
     */
    const TYPE_NAME  = 1;
    /**
     * It's an IPv4 address.
     * @var int
     */
    const TYPE_IPV4  = 2;
    /**
     * It's an IPv6 address surrounded by square brackets.
     * @var int
     */
    const TYPE_IPV6  = 4; // IPv6

    /***************
     * Construction
     */

    /**
     * Create a new host URI component value object.
     * @param string $host Host URI component (hostname or IP address)
     *                     without the "//" delimiter, or an empty string.
     *                     The IPv6 addresses must be delimited with brackets.
     */
    public function __construct(string $host = '')
    {
        $this->value = $host;
    }

    /**************
     * Conversions
     */

    /**
     * Return the URI component string without escaping the reserved characters.
     * @return string The URI component.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Build the URI component string representation.
     * The reserved characters ":","/","?","#" and "@" should be escaped.
     * «(...) producers and normalizers should use lowercase for registered
     * name and hexadecimal addresses (...)»
     * «When a non-ASCII registered name represents an internationalized domain
     * name intended for resolution via the DNS, the name must be transformed to
     * the IDNA encoding [RFC3490] prior to name lookup.»
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string URI component. It should not have the "//" delimiter.
     * @todo Make sure encoding a punycode is safe and if it works with web browsers.
     */
    public function toURIString(): string
    {
        switch ($this->getType()) {
            case self::TYPE_NAME:
                return rawurlencode(rawurldecode(
                    (new Punycode())->encode($this->value)
                ));

            case self::TYPE_IPV4:
                return inet_ntop(inet_pton($this->value));
            
            case self::TYPE_IPV6:
                $ipAddress = substr($this->value, 1, -1);
                return '['.inet_ntop(inet_pton($ipAddress)).']';
        }
        return '';
    }

    /**
     * Split the host by the separators.
     * Examples:
     * - If it's "www.test.com", it should return ["www","test","com"].
     * - If it's "127.0.0.8", it should return ["127","0","0","8"].
     * - If it's "[2001:db8::ff00:42:8329]", it should return ["2001","db8","","ff00","42","8329"];
     * @return string[] Hostname labels or IP fields.
     */
    public function toArray(): array
    {
        switch ($this->getType()) {
            case self::TYPE_NAME:
                return mb_split('[.]', $this->value);

            case self::TYPE_IPV4:
                return explode('.', $this->value);

            case self::TYPE_IPV6:
                return explode(':', trim($this->value, '[]'));
        }
        return array();
    }

    /**********
     * Changes
     */

    /**
     * If it's an hostname, create a new object with encoded punycode.
     * @uses Punycode::encode
     * @return self The encoded hostname or the same object.
     */
    public function toPunycode(): self
    {
        if ($this->getType() !== self::TYPE_NAME) {
            return $this;
        }

        $host = clone $this;
        $host->value = (new Punycode())->encode($this->value);
        return $host;
    }

    /**
     * If it's an hostname, create a new object with decoded punycode.
     * @uses Punycode::decode
     * @return self The decoded hostname or the same object.
     */
    public function toUnicode(): self
    {
        if ($this->getType() !== self::TYPE_NAME) {
            return $this;
        }

        $host = clone $this;
        $host->value = (new Punycode())->decode($this->value);
        return $host;
    }

    /**********
     * Getters
     */

    /**
     * Check if the host is empty.
     * @return bool Is the host empty?
     */
    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    /**
     * Return the host type.
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.2
     * «If host matches the rule for IPv4address, then it should be
     * considered an IPv4 address literal and not a reg-name (...)»
     * «A host identified by an Internet Protocol literal address, version 6
     * [RFC3513] or later, is distinguished by enclosing the IP literal
     *within square brackets ("[" and "]").»
     * @return int Type:
     *             - self::TYPE_EMPTY;
     *             - self::TYPE_NAME;
     *             - self::TYPE_IPV4;
     *             - or self::TYPE_IPV6;
     */
    public function getType(): int
    {
        if ($this->type === null) {
            $this->type = self::TYPE_NAME;
            if ($this->value === '') {
                $this->type = self::TYPE_EMPTY;
            } elseif ($this->isIPv6()) {
                $this->type = self::TYPE_IPV6;
            } elseif (filter_var($this->value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $this->type = self::TYPE_IPV4;
            }
        }
        return $this->type;
    }

    /**
     * @return bool Is it a IPv6 address surrounded by square brackets.
     * @codeCoverageIgnore
     */
    private function isIPv6(): bool
    {
        if ($this->value[0]!=='[' || substr($this->value, -1)!==']') {
            return false;
        }
        return filter_var(substr($this->value, 1, -1), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
}
