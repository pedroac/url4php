<?php
/**
 * StripDuplicatedSlashesRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Path;

/**
 * Removing duplicate slashes normalization rule.
 * It's most of the times safe with URLs.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_usually_preserve_semantics
 */
class StripDuplicatedSlashesRule implements Rule
{
    /**
     * Remove duplicated slashes, turning "//" into "/".
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $pathStr = preg_replace('~(//+)~u', '/', $components->getPath());
        $components->setPath(new Path($pathStr));
    }
}
