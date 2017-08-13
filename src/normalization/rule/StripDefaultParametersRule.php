<?php
/**
 * StripDefaultParametersRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Query;

/**
 * Removing default query parameters normalization rule.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_change_semantics
 * @see https://tools.ietf.org/html/rfc3986#section-3.2.3
 */
class StripDefaultParametersRule implements Rule
{
    /**
     * The parameters that should be removed.
     * @var array
     */
    private $defaultParameters;

    /**
     * Create new normalization rule.
     * @param array $defaultParameters The parameters that should be removed.
     */
    public function __construct(array $defaultParameters)
    {
        $this->defaultParameters = $defaultParameters;
    }

    /**
     * Remove the parameters that are the same as the specified parameters.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $parameters = $components->getQuery()->toArray();
        foreach ($parameters as $k => $v) {
            if (isset($this->defaultParameters[$k])
               && $this->defaultParameters[$k] == $v) {
                unset($parameters[$k]);
            }
        }
        $components->setQuery(Query::fromArray($parameters));
    }
}
