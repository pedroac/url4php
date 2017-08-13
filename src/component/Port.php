<?php
/**
 * Port class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url\component;

/**
 * Port URI component.
 * It should be immutable.
 */
class Port
{
    /**
     * Port number or NULL.
     * @var int|null
     */
    private $value;

    /****************
     * Constructions
     */

    /**
     * Create new port URI component value object.
     * @param int $value Port number or NULL.
     */
    public function __construct(int $value = null)
    {
        $this->value = $value;
    }

    /**************
     * Conversions
     */

    /**
     * Build the port URI component without the ":" delimiter.
     * @return string The port number or an empty string.
     */
    public function __toString(): string
    {
        return $this->isEmpty() ? '' : (string)$this->value;
    }

    /**
     * Build the URI component with the ":" delimiter.
     * «(...) should omit the ":" delimiter that separates host from port if
     * the port component is empty»
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.3
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI component or an empty string.
     */
    public function toURIString(): string
    {
        return $this->value===null ? '' : ':'.$this->value;
    }

    /**********
     * Getters
     */

    /**
     * Check if the port number is undefined.
     * @return bool Is the port number undefined?
     */
    public function isEmpty(): bool
    {
        return $this->value === null;
    }

    /**
     * Return the porn number.
     * @return int Port number or zero if it's undefined.
     */
    public function getNumber(): int
    {
        return $this->value ?: 0;
    }
}
