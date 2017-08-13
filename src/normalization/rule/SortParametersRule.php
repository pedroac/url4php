<?php
/**
 * SortParametersRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Query;

/**
 * Sorting the query parameters normalization rule.
 * This rule is most of the times safe: It assumes that the parameters order is not relevant.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_change_semantics
 */
class SortParametersRule implements Rule
{
    /**
     * Sort query string parameters into alphabetic order.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $parameters = $components->getQuery()->toArray();
        ksort($parameters);
        $this->sortKeys($parameters);
        $components->setQuery(Query::fromArray($parameters));
    }

    /**
     * Sort an array into alphatetic keys order and each value if it's an array.
     * @param  array  $subParameters Parameters that should be ordered.
     * @codeCoverageIgnore
     */
    private function sortKeys(array &$subParameters): void
    {
        foreach ($subParameters as $k => $v) {
            if (is_array($v)) {
                ksort($v);
                $subParameters[$k] = $v;
                $this->sortKeys($v);
            }
        }
    }
}
