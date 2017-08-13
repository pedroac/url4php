<?php
/**
 * StripDefaultPortRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Port;
use pedroac\url\SchemesPortsMap;

/**
 * Removing the default port normalization rule.
 * It's always safe, assuming the default ports are used and aren't change.
 * @see https://tools.ietf.org/html/rfc3986#section-6.2.3
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_preserve_semantics
 */
class StripDefaultPortRule implements Rule
{
    /**
     * Schemes/ports mapping.
     * @var SchemesPortsMap
     */
    private $schemesPortsMap;

    /**
     * Create a new normalizaton rule.
     * If no arguments are provided, the default schemes/ports mapping should
     * be used.
     * @param array $schemesPortsMap Schemes/ports mapping.
     *                               The keys should be the schemes.
     *                               The values should be the ports numbers.
     */
    public function __construct(array $schemesPortsMap = null)
    {
        if ($schemesPortsMap === null) {
            $this->schemesPortsMap = SchemesPortsMap::default();
            return;
        }
        $this->schemesPortsMap = new SchemesPortsMap($schemesPortsMap);
    }

    /**
     * Create a new object with schemes/ports mapping from a CSV file.
     * The first column should have the schemes, the second column should have
     * the ports numbers.
     * @param string $filePath A file path or URL to a readable CSV file.
     * @return self
     */
    public static function fromFile(string $filePath): self
    {
        $obj = new self([]);
        $obj->schemesPortsMap = SchemesPortsMap::fromFile($filePath);
        return $obj;
    }

    /**
     * Remove port URI component if it's the default for the URI's scheme.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $scheme = $components->getScheme();
        $port   = $components->getPort()->getNumber();
        if ($this->schemesPortsMap->isSchemePort($scheme, $port)) {
            $components->setPort(new Port);
        }
    }
}
