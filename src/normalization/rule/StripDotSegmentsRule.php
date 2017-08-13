<?php
/**
 * StripDotSegmentsRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;

/**
 * Removing dot-segments normalization rule.
 * It's most of the times safe with URLs.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_usually_preserve_semantics
 * @see https://tools.ietf.org/html/rfc3986#section-3.3
 */
class StripDotSegmentsRule implements Rule
{
    /**
     * Remove the segments ".." and "." resolving the path URI component.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $components->setPath($components->getPath()->resolve());
    }
}
