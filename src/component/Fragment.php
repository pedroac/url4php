<?php
/**
 * Fragment class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url\component;

/**
 * Fragment URI component.
 * It should be immutable.
 */
class Fragment
{
    /**
     * Fragment label.
     * @var string
     */
    private $value;

    /***************
     * Construction
     */

    /**
     * Create a new fragment URI component value object.
     * @param string|null $value Fragment label, without the "#" delimiter,
     *                           or NULL.
     */
    public function __construct(string $value = null)
    {
        $this->value = $value;
    }

    /**************
     * Conversions
     */

    /**
     * The URI component without the "#" delimiter.
     * @return string The fragment label.
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * The URI component with the "#" delimiter, or an empty string.
     * @return string The URI component.
     */
    public function toURIString(): string
    {
        if ($this->value === null) {
            return '';
        }
        return '#'.rawurlencode(rawurldecode($this->value));
    }

    /**********
     * Getters
     */

    /**
     * Check if the fragment is empty.
     * A fragment is empty if it's undefined oe if its label is empty.
     * @return bool Is the fragment empty?
     */
    public function isEmpty(): bool
    {
        return !isset($this->value[0]);
    }
}
