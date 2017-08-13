<?php
/**
 * Base class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.1.0
 */
namespace pedroac\url;

use pedroac\url\component\Query;

/**
 * Base URL.
 * It can be used to resolve relative URLs.
 * It should be immutable.
 * «(...) must be absolute (...)»
 * @see https://www.cs.tut.fi/~jkorpela/HTML3.2/5.7.html#BASE
 * @see https://tools.ietf.org/html/rfc3986#section-5.1
 */
class Base
{
    /**
     * Parsed absolute URL.
     * @var Parsed
     */
    private $parsed;

    /****************
     * Constructions
     */

    /**
     * Create a new URL base.
     * The URL must be absolute.
     * Query string and fragment are removed.
     * @param URL $url Absolute URL.
     * @throws \InvalidArgumentException
     */
    public function __construct(URL $url)
    {
        if (!$url->isAbsolute()) {
            $errorMessage = sprintf(gettext('URL must be absolute: %s.'), $url);
            throw new \InvalidArgumentException($errorMessage);
        }
        $this->parsed = new Parsed($url);
    }

    /**
     * Create a new object from a URL string.
     * @param  string $url Absolute URL
     * @return self        URL base.
     */
    public static function fromString(string $url): self
    {
        return new self(new URL($url));
    }

    /**************
     * Conversions
     */

    /**
     * Return parsed URL string
     * @return string URL.
     */
    public function __toString(): string
    {
        return (string)$this->parsed;
    }

    /**
     * Resolve an URL creating an absolute URL from it.
     * @param URL  $url URL to be resolved.
     * @return URL      An absolute URL.
     */
    public function toAbsolute(URL $url): URL
    {
        if ((string)$url === '') {
            return $this->parsed->getURL();
        }

        $parsed = new Parsed($url);
        if (!$parsed->getScheme()->isEmpty()) {
            return $url;
        }
        if (!$parsed->getHost()->isEmpty()) {
            return new URL($this->parsed->getScheme().':'.$url);
        }

        $thisComponents = $this->parsed->toComponents();
        if (!$parsed->getPath()->isEmpty()) {
            $thisComponents->setPath($this->parsed->getPath()
                                     ->merge($parsed->getPath()));
            $thisComponents->setQuery(new Query);
        }
        if (!$parsed->getQuery()->isEmpty()) {
            $thisComponents->setQuery($parsed->getQuery());
        }
        if (!$parsed->getFragment()->isEmpty()) {
            $thisComponents->setFragment($parsed->getFragment());
        }
        return new URL($thisComponents);
    }

    /**********
     * Getters
     */

    /**
     * Return the parsed URL.
     * @return URL The absolute URL.
     */
    public function getURL(): URL
    {
        return $this->parsed->getURL();
    }
}
