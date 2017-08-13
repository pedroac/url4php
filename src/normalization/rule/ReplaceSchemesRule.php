<?php
/**
 * ReplaceSchemesRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Scheme;

/**
 * Limiting protocols normalization rule.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_change_semantics
 */
class ReplaceSchemesRule implements Rule
{
    /**
     * The keys are the schemes that should be replaced.
     * The values are related replacement schemes.
     * @var array
     */
    private $schemesReplacements;

    /**
     * Create a new normalization rule.
     * @param array $schemesReplacements The keys are the schemes that should be replaced.
     *                                   The values are related replacement schemes.
     */
    public function __construct(array $schemesReplacements = ['http'=>'https'])
    {
        $this->schemesReplacements = $schemesReplacements;
    }

    /**
     * Limit schemes:
     * the URL should never have some specified schemes.
     * If one of them is used, it should be replaced by a another specified scheme.
     * The default should be:
     * - "http" should be replaced by "https".
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $scheme = strtolower($components->getScheme());
        if (isset($this->schemesReplacements[$scheme])) {
            $components->setScheme(new Scheme($this->schemesReplacements[$scheme]));
        }
    }
}
