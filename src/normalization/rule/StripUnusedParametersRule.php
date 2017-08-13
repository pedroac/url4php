<?php
/**
 * StripUnusedParametersRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Query;

/**
 * Removing unused query variables normalization rule.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_change_semantics
 */
class StripUnusedParametersRule implements Rule
{
    /**
     *
     * @var string[]
     */
    private $paramsToFilter;

    /**
     * Create a new normalization rule.
     * @param string[] $paramsToFilter A list of query variables to be kept.
     *                 All the other parameters should be removed.
     */
    public function __construct(array $paramsToFilter)
    {
        $this->paramsToFilter = $paramsToFilter;
    }

    /**
     * Remove query string variables that are not among a specified list.
     * It's useful to remove all the parameters that are not going to be used.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $parameters = $components->getQuery()->toParameters();
        $parameters->filter($this->paramsToFilter);
        $components->setQuery(Query::fromParameters($parameters));
    }
}
