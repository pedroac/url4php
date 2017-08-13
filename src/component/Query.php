<?php
/**
 * Query class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url\component;

/**
 * Query string URI component.
 * It should be immutable.
 */
class Query
{
    /**
     * Query string (eg: "a=b&c=d").
     * @var string|null
     */
    private $value;

    /**
     * Cached parameters, filled QueryString::toArray is called.
     * @var array
     */
    private $params=null;

    /****************
     * Constructions
     */

    /**
     * Create a new query string URI component value object.
     * @param string|null $string Query string without the "?" delimiter,
     *                            or NULL.
     */
    public function __construct(string $string = null)
    {
        $this->value = $string;
    }

    /**
     * Create a new query string from an associative array.
     * @param array $parameters Query string parameters.
     * @return self
     */
    public static function fromArray(array $parameters): self
    {
        if (empty($parameters)) {
            return new self(null);
        }
        return new self(http_build_query($parameters));
    }

    /**
     * Create a new object from parameters.
     * @see self::toParameters
     * @param Parameters $params
     * @return self
     */
    public static function fromParameters(Parameters $params): self
    {
        return self::fromArray($params->toArray());
    }

    /**************
     * Conversions
     */

    /**
     * Return query string without the "?" delimiter.
     * @return string The URI component without the "?" delimiter.
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * Build the URI component with the "?" delimiter or an empty string.
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string URI component.
     */
    public function toURIString(): string
    {
        if ($this->value === null) {
            return '';
        }
        return '?'
               . preg_replace_callback('~[^=&]+~',
                                        function ($matches) {
                                            return rawurlencode(rawurldecode($matches[0]));
                                        },
                                        $this->value
               );
    }

    /**
     * Build an associative array from the query string parameters.
     * Each key should be a parameter name.
     * Each value should be the associated parameted value.
     * @return array All parameters as an associative array.
     */
    public function toArray(): array
    {
        if ($this->params) {
            return $this->params;
        }
        mb_parse_str($this->value, $this->params);
        return $this->params;
    }

    /**
     * Returns query string mutable parameters.
     * Changes won't affect the QueryString instance.
     * @return Parameters
     */
    public function toParameters(): Parameters
    {
        return new Parameters($this->toArray());
    }

    /**********
     * Changes
     */

    /**
     * Create a new object with a sub query appended.
     * @param string $subQueryString Sub query string to be appended.
     * @return self
     */
    public function append(string $subQueryString): self
    {
        return new Query($this->value.'&'.$subQueryString);
    }

    /**
     * Create new object with changes.
     * @uses Parameters::add
     * @uses Parameters::remove
     * @param array     $newParams    New parameters as an associative array.
     *                                If a value is NULL, then the parameter
     *                                with the same should be removed.
     * @param string[]  $keysToRemove A list of parameters names to be removed.
     * @return self
     */
    public function change(
        array $newParams,
        array $keysToRemove = array()
    ): self {
        $parameters = new Parameters($this->toArray());
        $parameters->remove($keysToRemove);
        $parameters->add($newParams);
        return self::fromParameters($parameters);
    }

    /**********
     * Getters
     */

    /**
     * Check if the query string is empty.
     * It's empty if it does not have parameters.
     * @return bool Does the query string have any parameters?
     */
    public function isEmpty(): bool
    {
        return !isset($this->value[0]);
    }

    /**
     * Get value from a parameter with a specified name.
     * @param  string $paramKey Name of the parameter that should be accessed.
     * @return mixed            Parameter value or NULL.
     */
    public function get(string $paramKey)
    {
        if ($this->params === null) {
            $this->params = $this->toArray();
        }
        if (!isset($this->params[$paramKey])) {
            return null;
        }
        return $this->params[$paramKey];
    }

    /**
     * Check if there's a parameter with a specified name.
     * @param  string $paramKey Name of the parameter.
     * @return bool             Has a parameter with the specified name?
     */
    public function has(string $paramKey): bool
    {
        if ($this->params === null) {
            $this->params = $this->toArray();
        }
        return isset($this->params[$paramKey]);
    }
}
