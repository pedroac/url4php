<?php
/**
 * StripFragmentRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Fragment;

/**
 * Removing the fragment normalization rule.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_change_semantics
 */
class StripFragmentRule implements Rule
{
    /**
     * Remove the fragment URI component.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $components->setFragment(new Fragment(null));
    }
}
