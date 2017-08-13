<?php
/**
 * Parsed class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url;

use pedroac\url\URL;
use pedroac\url\Components;
use pedroac\url\component\Scheme;
use pedroac\url\component\Authority;
use pedroac\url\component\Host;
use pedroac\url\component\Port;
use pedroac\url\component\UserInfo;
use pedroac\url\component\Path;
use pedroac\url\component\Query;
use pedroac\url\component\Fragment;

/**
 * Parsed URL and its the components.
 * It should be immutable.
 * @property-read Scheme      $scheme
 * @property-read Authority   $authority
 * @property-read UserInfo    $userInfo
 * @property-read Host        $host
 * @property-read Port        $port
 * @property-read Path        $path
 * @property-read QueryString $queryString
 * @property-read Fragment    $fragment
 */
class Parsed
{
    use AccessorTrait;

    /**
     * The URL that was parsed.
     * @var URL
     */
    private $url;

    /**
     * URL's scheme.
     * @var Scheme
     */
    private $scheme;

    /**
     * URL's authority.
     * @var Authority
     */
    private $authority;

    /**
     * URL's path.
     * @var Path
     */
    private $path;

    /**
     * URL's query string.
     * @var Query
     */
    private $query;

    /**
     * URL's fragment.
     * @var Fragment
     */
    private $fragment;

    /**
     * Regex pattern to match a scheme URI component with de delimiter.
     * @var string
     */
    const REGEX_SCHEME = '([^:/?#]+:)?';
    /**
     * Regex pattern to match a authority URI component with de delimiter.
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @var string
     */
    const REGEX_AUTHORITY = '(//[^/?#]*)?';
    /**
     * Regex pattern to match a path URI component.
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @var string
     */
    const REGEX_PATH = '([^?#]*)';
    /**
     * Regex pattern to match a query string URI component with de delimiter.
     * @var string
     */
    const REGEX_QUERY = '(\?[^#]*)?';
    /**
     * Regex pattern to match a fragment URI component with de delimiter.
     * @var string
     */
    const REGEX_FRAGMENT = '(#.*)?';
    /**
     * Regex pattern to match the part before the path URI component.
     * @var string
     */
    const REGEX_BEFORE_PATH = '([^:/?#]*:?(?://[^/?#]*)?)';
    /**
     * Regex pattern to match the part before the query string URI component.
     * @var string
     */
    const REGEX_BEFORE_QUERY = '([^#?]*)';
    /**
     * Regex pattern to match the part before the fragment URI component.
     * @var string
     */
    const REGEX_BEFORE_FRAGMENT = '([^#]*)';
    /**
     * Regex pattern to match a URL components with their delimiters included.
     * @var string
     */
    const REGEX_URL = '~^'
                      . self::REGEX_SCHEME    // 1 => Scheme
                      . self::REGEX_AUTHORITY // 2 => Authority
                      . self::REGEX_PATH      // 3 => Path
                      . self::REGEX_QUERY     // 4 => Query
                      . self::REGEX_FRAGMENT  // 5 => Fragment
                      . '~u';

    /***************
     * Constructors
     */

    /**
     * Parse URL and keep its components.
     * It follows the RFC 3986 URI Generic Syntax.
     * @see https://tools.ietf.org/html/rfc3986#appendix-B
     * @param URL $url URL that should be parsed.
     */
    public function __construct(URL $url)
    {
        $this->url = $url;

        $matches = array();
        preg_match(self::REGEX_URL, $url, $matches);
        $this->scheme     = new Scheme(isset($matches[1]) ? rtrim($matches[1], ':') : '');
        $this->authority  = Authority::fromString($matches[2] ?? '');
        $this->path       = new Path($matches[3] ?? '');

        $this->query = isset($matches[4][0])
                           ? new Query(ltrim($matches[4], '?'))
                           : new Query(null);

        $this->fragment = isset($matches[5][0])
                              ? new Fragment(ltrim($matches[5], '#'))
                              : new Fragment(null);
    }

    /**
     * Parse an URL string.
     * @param string $url URL that should be parsed.
     * @return self
     */
    public static function fromString(string $url): self
    {
        return new self(new URL($url));
    }

    /**************
     * Conversions
     */

    /**
     * Return the parsed URL string.
     * @return string The URL that was parsed.
     */
    public function __toString(): string
    {
        return (string)$this->url;
    }

    /**
     * Create a mutable "bag" of components.
     * The "bag" should start with the same URL's components.
     * @return Components Mutable components.
     */
    public function toComponents(): Components
    {
        return new Components($this->scheme,
                              $this->getAuthority(),
                              $this->path,
                              $this->query,
                              $this->fragment);
    }

    /**********
     * Getters
     */

    /**
     * Return the parsed URL.
     * @return URL The URL that was parsed.
     */
    public function getURL(): URL
    {
        return $this->url;
    }

    /**
     * Return the URL's scheme.
     * @return Scheme URL's scheme.
     */
    public function getScheme(): Scheme
    {
        return $this->scheme;
    }

    /**
     * Return the URL's host.
     * @return Host URL's host.
     */
    public function getHost(): Host
    {
        return $this->authority->getHost();
    }

    /**
     * Return the URL's port.
     * @return Port URL's port.
     */
    public function getPort(): Port
    {
        return $this->authority->getPort();
    }

    /**
     * Return the URL's userinfo.
     * @return UserInfo URL's userinfo.
     */
    public function getUserInfo(): UserInfo
    {
        return $this->authority->getUserInfo();
    }

    /**
     * Return the URL's authority.
     * @return Authority URL's authority.
     */
    public function getAuthority(): Authority
    {
        return $this->authority;
    }

    /**
     * Return the URL's path.
     * @return Path URL's path.
     */
    public function getPath(): Path
    {
        return $this->path;
    }

    /**
     * Return the the URL's query string.
     * @return Query URL's query string.
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Return the URL's fragment.
     * @return Fragment URL's fragment.
     */
    public function getFragment(): Fragment
    {
        return $this->fragment;
    }
}
