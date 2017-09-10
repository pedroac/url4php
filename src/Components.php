<?php
/**
 * Components class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url;

use pedroac\url\component\Scheme;
use pedroac\url\component\Authority;
use pedroac\url\component\UserInfo;
use pedroac\url\component\Host;
use pedroac\url\component\Port;
use pedroac\url\component\Path;
use pedroac\url\component\Query;
use pedroac\url\component\Fragment;

/**
 * Mutable URI components bag.
 * @property Scheme      $scheme
 * @property Authority   $authority
 * @property UserInfo    $userInfo
 * @property Host        $host
 * @property Port        $port
 * @property Path        $path
 * @property Query       $query
 * @property Fragment    $fragment
 */
class Components
{
    use AccessorTrait;

    /**
     * @var Scheme
     */
    private $scheme;

    /**
     * @var UserInfo
     */
    private $userInfo;

    /**
     * @var Host
     */
    private $host;

    /**
     * @var Port
     */
    private $port;

    /**
     * @var Path
     */
    private $path;

    /**
     *
     * @var Query
     */
    private $query;

    /**
     * @var Fragment
     */
    private $fragment;

    /****************
     * Constructors
     */

    /**
     * Create a new mutable "bag" of components.
     * If an argument is NULL, the corresponding component will be empty.
     * @param Scheme      $scheme
     * @param Authority   $authority
     * @param Path        $path
     * @param Query       $query
     * @param Fragment    $fragment
     */
    public function __construct(
        Scheme $scheme = null,
        Authority $authority = null,
        Path $path = null,
        Query $query = null,
        Fragment $fragment = null
    ) {
        $this->scheme   = $scheme ? : new Scheme();
        $this->path     = $path ? : new Path;
        $this->query    = $query ? : new Query;
        $this->fragment = $fragment ? : new Fragment;

        if ($authority) {
            $this->host     = $authority->getHost();
            $this->port     = $authority->getPort();
            $this->userInfo = $authority->getUserInfo();
            return;
        }
        $this->host     = new Host;
        $this->port     = new Port;
        $this->userInfo = new UserInfo;
    }

    /*************
     * Converters
     */

    /**
     * Build an URL from the components.
     * «(...) If a URI contains an authority component, then the path
     * component must either be empty or begin with a slash ("/") character.»
     * @see https://tools.ietf.org/html/rfc3986#section-3
     * @return string Built URL from the components.
     */
    public function __toString(): string
    {
        $authority = $this->getAuthority();
        return   $this->scheme->toUriString()
               . $authority->toUriString()
               . $this->path->toUriString(!$authority->isEmpty())
               . $this->query->toUriString()
               . $this->fragment->toUriString();
    }

    /**********
     * Setters
     */

    /**
     * Replace all the components.
     * @param  Components $components Replacement components.
     */
    public function replaceWith(Components $components): void
    {
        $this->scheme   = $components->scheme;
        $this->userInfo = $components->userInfo;
        $this->host     = $components->host;
        $this->port     = $components->port;
        $this->path     = $components->path;
        $this->query    = $components->query;
        $this->fragment = $components->fragment;
    }

    /**
     * Replace the scheme URI component.
     * @param Scheme $scheme The scheme URI component to replace.
     */
    public function setScheme(Scheme $scheme): void
    {
        $this->scheme = $scheme;
    }

    /**
     * Replace the authority URI component.
     * The host, port and userinfo should be replaced.
     * @param Authority $authority The authority URI component to replace.
     */
    public function setAuthority(Authority $authority): void
    {
        $this->host     = $authority->getHost();
        $this->port     = $authority->getPort();
        $this->userInfo = $authority->getUserInfo();
    }

    /**
     * Replace the userinfo URI component.
     * @param UserInfo $userInfo The userinfo URI component to replace.
     */
    public function setUserInfo(UserInfo $userInfo)
    {
        $this->userInfo = $userInfo;
    }

    /**
     * Replace the host URI component.
     * @param Host $host The host URI component to replace.
     */
    public function setHost(Host $host): void
    {
        $this->host = $host;
    }

    /**
     * Replace the port URI component.
     * @param Port $port The port URI component to replace.
     */
    public function setPort(Port $port): void
    {
        $this->port = $port;
    }

    /**
     * Replace the path URI component.
     * @param Path $path The path URI component to replace.
     */
    public function setPath(Path $path): void
    {
        $this->path = $path;
    }

    /**
     * Replace the query string URI component.
     * @param Query $query The query string URI component to replace.
     */
    public function setQuery(Query $query): void
    {
        $this->query = $query;
    }

    /**
     * Replace the fragment URI component.
     * @param Fragment $fragment The fragment URI component to replace.
     */
    public function setFragment(Fragment $fragment): void
    {
        $this->fragment = $fragment;
    }

    /**********
     * Getters
     */

    /**
     * Return the scheme URI component.
     * @return Scheme The scheme URI component.
     */
    public function getScheme(): Scheme
    {
        return $this->scheme;
    }

    /**
     * Return the authority URI component.
     * @return Host The host URI component.
     */
    public function getHost(): Host
    {
        return $this->host;
    }

    /**
     * Return the port URI component.
     * @return Port The port URI component.
     */
    public function getPort(): Port
    {
        return $this->port;
    }

    /**
     * Return the userinfo URI component.
     * @return UserInfo The userinfo URI component.
     */
    public function getUserInfo(): UserInfo
    {
        return $this->userInfo;
    }

    /**
     * Return the authority URI component.
     * @return Authority The authority URI component.
     */
    public function getAuthority(): Authority
    {
        return new Authority($this->host, $this->port, $this->userInfo);
    }

    /**
     * Return the path URI component.
     * @return Path The path URI component.
     */
    public function getPath(): Path
    {
        return $this->path;
    }

    /**
     * Return the query string URI component.
     * @return Query The query string URI component.
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Return the fragment URI component.
     * @return Fragment The fragment URI component.
     */
    public function getFragment(): Fragment
    {
        return $this->fragment;
    }
}
