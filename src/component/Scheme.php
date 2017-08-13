<?php
/**
 * Scheme class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url\component;

/**
 * Scheme URI component.
 * It should be immutable.
 */
class Scheme
{
    /**
     * Scheme (example: "http").
     * @var string
     */
    private $value;

    /****************
     * Constructions
     */

    /**
     * Create a new scheme URI component value object.
     * @param string $scheme Scheme without the ":" delimiter or an empty string.
     */
    public function __construct(string $scheme = '')
    {
        $this->value = $scheme;
    }

    /**************
     * Conversions
     */

    /**
     * The URI component without the ":" delimiter.
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Build the URI component with the ":" delimiter or an empty string.
     * «(...)should only produce lowercase scheme names for consistency.»
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string URI component with the ":" delimiter or an empty string.
     */
    public function toURIString(): string
    {
        if ($this->value === '') {
            return '';
        }
        return urlencode(urldecode(strtolower($this->value))).':';
    }

    /**********
     * Getters
     */

    /**
     * Check if the scheme is empty.
     * @return string Is the scheme undefined?
     */
    public function isEmpty(): bool
    {
        return $this->value === '';
    }
}
