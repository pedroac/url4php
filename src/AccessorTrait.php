<?php
/**
 * AccessorTrait trait.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.1
 */
namespace pedroac\url;

/**
 * @ignore
 * @codeCoverageIgnore
 */
trait AccessorTrait
{
    /**
     * @param  string $property
     * @return mixed
     */
    public function __get($property)
    {
        $method = 'get'.ucfirst($property);
        if (!method_exists($this, $method)) {
            $message = sprintf('Cannot read property %s::$%s.',
                                get_class(), $property);
            throw new \BadMethodCallException($message);
        }
        return $this->$method();
    }

    /**
     * @param  string $property
     * @param  mixed  $value
     */
    public function __set($property, $value)
    {
        $method = 'set'.ucfirst($property);
        if (!method_exists($this, $method)) {
            $message = sprintf('Cannot write property %s::$%s.',
                                get_class(), $property);
            throw new \BadMethodCallException($message);
        }
        return $this->$method($value);
    }
}
