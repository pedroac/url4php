<?php
/**
 * Parameters class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url\component;

/**
 * Mutable query string parameters.
 */
class Parameters
{
    /**
     * Parameters as an associative array.
     * @var array
     */
    private $params;

    /***************
     * Construction
     */

    /**
     * Create a query string parameters mutable object.
     * @param array $params Associative array.
     */
    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    /**************
     * Conversions
     */

    /**
     * Return the parameters as an associative array.
     * The keys are the parameters names.
     * The values are the parameters values.
     * @return array The parameters as an associative array.
     */
    public function toArray(): array
    {
        return $this->params;
    }

    /**********
     * Changes
     */

    /**
     * Add parameters.
     * Parameters with the same names should be replaced.
     * @param array $newParams An associative array with new parameters.
     */
    public function add(array $newParams): void
    {
        foreach ($newParams as $k => $v) {
            $this->params[$k] = $v;
        }
    }

    /**
     * Remove parameters with specified names.
     * @param string[] $keysToRemove A list of parameters names that should be removed.
     */
    public function remove(array $keysToRemove): void
    {
        foreach ($keysToRemove as $k) {
            unset($this->params[$k]);
        }
    }

    /**
     * Filter parameters,
     * ie: the parameters with the specified names should remain
     * and all the others should be removed.
     * @param string[] $keysToFilter A list of parameters names that should be filtered.
     */
    public function filter(array $keysToFilter): void
    {
        $this->params = array_intersect_key($this->toArray(),
                                            array_flip($keysToFilter));
    }

    /**********
     * Getters
     */

    /**
     * Get value from a parameter with a specified name.
     * @param  string $paramKey Name of the parameter that should be accessed.
     * @return mixed            Parameter value or NULL if the parameter does not exist.
     */
    public function get(string $paramKey)
    {
        if (!isset($this->params[$paramKey])) {
            return null;
        }
        return $this->params[$paramKey];
    }

    /**
     * Check if there's a parameter with a specified name.
     * @param string $paramKey Name of the parameter.
     * @return bool            Has a parameter with the specified name?
     */
    public function has(string $paramKey): bool
    {
        return isset($this->params[$paramKey]);
    }
}
