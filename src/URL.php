<?php
/**
 * URL class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.1.0
 */
namespace pedroac\url;

use pedroac\url\component\Scheme;
use pedroac\url\component\Authority;
use pedroac\url\component\Path;
use pedroac\url\component\Query;
use pedroac\url\component\Fragment;
use pedroac\url\SchemesPortsMap;

/**
 * URL value object.
 * It might be a relative or an absolute URL.
 * It should be immutable.
 */
class URL
{
    /**
     * URL.
     * @var string
     */
    private $value;

    /**
     * Current session URL.
     * @var self
     */
    private static $current;

    /****************
     * Constructors
     */

    /**
     * Create a new URL value object.
     * @param string $value Absolute or relative URL.
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Build an URL from the global $_SERVER variable or a similar associative
     * array.
     * Required keys: "SERVER_NAME", "SERVER_PORT" and "REQUEST_URI".
     * Optional keys: "REQUEST_SCHEME", "HTTPS" and "QUERY_STRING".
     * @param array $vars The global $_SERVER variable or an associative array
     *                    with the same keys required to build a URL string.
     * @return self       URL built from the array.
     * @throws \InvalidArgumentException
     */
    public static function fromServerVars(array $vars): self
    {
        foreach (['SERVER_NAME', 'SERVER_PORT','REQUEST_URI'] as $requiredKey) {
            if (!isset($vars[$requiredKey])) {
                $message = sprintf('Invalid server array: missing "%s" key.', $requiredKey);
                throw new \InvalidArgumentException($message);
            }
        }

        $value = self::getServerVarsBase($vars);
        $value .= $vars['REQUEST_URI'];
        if (isset($vars['QUERY_STRING']) && $vars['QUERY_STRING']) {
            $value .= "?{$vars['QUERY_STRING']}";
        }

        return new self($value);
    }

    /**
     * Build URL base (scheme and authority) from the global $_SERVER 
     * variable or a similar associative array.
     * @return string URL base.
     * @codeCoverageIgnore
     */
    static private function getServerVarsBase(array $vars): string
    {
        $scheme = isset($vars['REQUEST_SCHEME']) ? strtolower($vars['REQUEST_SCHEME']) : null;
        if (!$scheme) {
            $isSSL = (isset($vars['HTTPS']) && $vars['HTTPS'] !== 'off');
            $scheme = ($isSSL ? 'https' : 'http');
        }
        
        $value = "$scheme://{$vars['SERVER_NAME']}";
        if (!SchemesPortsMap::default()->isSchemePort($scheme, $vars['SERVER_PORT'])) {
            $value .= ":{$vars['SERVER_PORT']}";
        }
        return $value;
    }

    /**
     * Return the current URL.
     * The object is created only once and reused.
     * @return self Current URL.
     * @codeCoverageIgnore
     */
    public static function getCurrent(): self
    {
        if (!self::$current) {
            self::$current = self::fromServerVars(filter_input_array(INPUT_SERVER));
        }
        return self::$current;
    }

    /**************
     * Conversions
     */

    /**
     * Return the URL string.
     * @return string URL.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Parse the URL.
     * Return an immutable object with the split URI components.
     * Use the self::with to create a new URL with some different components.
     * @return Parsed The parsed URL and its components.
     */
    public function parse(): Parsed
    {
        return new Parsed($this);
    }

    /**********
     * Changes
     */

    /**
     * Create a new object with replaced components.
     * If an argument for a component is NULL, the component won't be replaced.
     * An empty component should remove the component from the URL string.
     * @param Scheme      $scheme      Scheme URI component to replace or be added.
     * @param Authority   $authority   Authority URI component to replace or be added.
     * @param Path        $path        Path URI component to replace or be added.
     * @param Query $query Query String URI component to replace or be added.
     * @param Fragment    $fragment    Fragment URI component to replace or be added.
     * @return self                    Copied URL with components changes.
     */
    public function with(
        Scheme $scheme = null,
        Authority $authority = null,
        Path $path = null,
        Query $query = null,
        Fragment $fragment = null
    ): self {
        $parse = $this->parse();
        $components = new Components();
        $components->setScheme($scheme ?: $parse->getScheme());
        $components->setAuthority($authority ?: $parse->getAuthority());
        $components->setPath($path ?: $parse->getPath());
        $components->setQuery($query ?: $parse->getQuery());
        $components->setFragment($fragment ?: $parse->getFragment());
        return new self($components);
    }

    /**
     * Create a new object with changed parameters.
     * @param array    $newParams New parameters as an associative array. A NULL value removes the parameter.
     * @param string[] $keysToRemove A list of parameters names to be removed.
     * @return self                  Copied URL with parameters changes.
     */
    public function changeParameters(
        array $newParams,
        array $keysToRemove = array()
    ): self {
        $matches = array();
        preg_match('~'
                   .Parsed::REGEX_BEFORE_QUERY // 1 before query string and fragment
                   .Parsed::REGEX_QUERY        // 2 query string
                   .'(.*)'                     // 3 fragment URI component
                   .'~u', $this->value, $matches);
        $beforeQuery = $matches[1] ?? '';
        $afterQuery  = $matches[3] ?? '';
        $query = isset($matches[2][0])
                    ? (new Query(ltrim($matches[2], '?')))->change($newParams, $keysToRemove)
                    : Query::fromArray($newParams);
        return new self($beforeQuery
                        .$query->toUriString()
                        .$afterQuery);
    }

    /**
     * Create a new URL with a specified path component.
     * @param Path $path Path URI component.
     * @return self      Copied URL with the path changed.
     */
    public function withPath(Path $path): self
    {
        $matches = array();
        preg_match('~'
                   . Parsed::REGEX_BEFORE_PATH // 1 before path, query string and fragment
                   . Parsed::REGEX_PATH        // 2 path
                   .'(.*)'                     // 3 query string and fragment
                   .'~u', $this->value, $matches);
        $beforePath = isset($matches[1]) ? $matches[1] : '';
        $afterPath  = isset($matches[3]) ? $matches[3] : '';
        return new self($beforePath
                        . $path->toUriString($beforePath!=='')
                        . $afterPath);
    }

    /**
     * Create a new object with a specified fragment.
     * @param Fragment $fragment Fragment URI component.
     * @return self              Copied URL with the fragment changed.
     */
    public function withFragment(Fragment $fragment): self
    {
        $fragmentUri = $fragment->toUriString();
        if ($this->value === '' || mb_substr($this->value, 0, 1) === '#') {
            return new self($fragmentUri);
        }
        $beforeFragment = mb_strstr($this->value, '#', true) ?: $this->value;
        return new self($beforeFragment . $fragmentUri);
    }

    /**********
     * Getters
     */

    /**
     * Check if the URL is absolute.
     * An absolute URL is an URL with a scheme and a host.
     * «(...) this pattern is mainly for Web documents (...)»
     * @see https://www.cs.tut.fi/~jkorpela/HTML3.2/3.5.html
     * @see https://tools.ietf.org/html/rfc3986#section-4.3
     * @return bool Is the URL absolute?
     */
    public function isAbsolute(): bool
    {
        return preg_match('~^[^:]+://[^/]~u',
                          $this->value);
    }
}
