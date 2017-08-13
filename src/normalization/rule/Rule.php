<?php
/**
 * Rule interface.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;

/**
 * Normalization rule.
 */
interface Rule
{
    /**
     * Apply the normalization rules to mutable "bag" of components.
     * @param Components $components The components that should be changed, if
     *                               they're not normalized.
     */
    public function apply(Components $components);
}
