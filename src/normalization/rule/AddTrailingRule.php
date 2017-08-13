<?php
/**
 * AddTrailingRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Path;

/**
 * Adding trailing / normalization rule.
 * It's most of the times safe with URLs.
 * @see https://tools.ietf.org/html/rfc3986#section-6
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_usually_preserve_semantics
 */
class AddTrailingRule implements Rule
{
    /**
     * Make sure that the path ends with a trailing slash.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $path = $components->getPath();
        if ($path === '' || mb_substr($path, -1) !== '/') {
            $components->setPath(new Path($path.'/'));
        }
    }
}
