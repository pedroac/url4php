<?php
/**
 * StripWWWRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Host;

/**
 * Removing or adding “www” as the first domain label normalization rule.
 * It's most of the times safe with URLs.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_change_semantics
 */
class StripWWWRule implements Rule
{
    /**
     * Remove the "www" subdomain.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $wwwStringLen = strlen('www.');
        $host = $components->getHost();
        if (strtolower(substr($host, 0, $wwwStringLen)) === 'www.') {
            $newHost = new Host(mb_substr($host, $wwwStringLen));
            $components->setHost($newHost);
        }
    }
}
