<?php
/**
 * Path class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.2.0
 */
namespace pedroac\url\component;

use pedroac\url\component\Path\SegmentsList;
use pedroac\url\component\Path\Segment;

/**
 * Path URI component.
 * It should be immutable.
 * It's assumed that it might have Unicode characters.
 */
class Path
{
    /**
     * Path URI component.
     * @var string
     */
    private $value;

    /****************
     * Constructions
     */

    /**
     * Create a new path URI component value object.
     * @param string $value Path URI component.
     */
    public function __construct(string $value = '')
    {
        $this->value = $value;
    }

    /**************
     * Conversions
     */

    /**
     * Return the path string.
     * @return string URI component.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Build the URI component string.
     * The characters "#" and "?" should be encoded.
     * «If a URI contains an authority component, then the path component
     *  must either be empty or begin with a slash ("/") character.»
     * «a URI reference (...) may be a relative-path reference, in which case the
     *  first path segment cannot contain a colon (":") character.»
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @param bool $hasAuthority If TRUE and the path is not empty, the "/"
     *                           prefix is added if it's missing.
     * @return string URI component or an empty string.
     */
    public function toURIString(bool $hasAuthority = false): string
    {
        if ($this->value === '') {
            return '';
        }

        if (!$hasAuthority && mb_substr($this->value, 0, 2) === '//') {
            $message = sprintf('The path cannot begin with two slash characters: %s.',
                               $this->value);
            throw new \RuntimeException($message);
        }

        $encoded = preg_replace_callback(
            '~[^/;=@]+~u',
            function ($matches) {
                return rawurlencode(urldecode($matches[0]));
            },
            $this->value
        );
        if ($hasAuthority && mb_substr($encoded, 0, 1) !== '/') {
            return '/'.$encoded;
        }
        return $encoded;
    }

    /**
     * Split the path into segments.
     * The segments order should be preserved.
     * The root and end trailing slashes should be ignored.
     * Example:
     *  - If the path is "/hello/world/", it should be returned ["hello", "world"].
     * @return array The path segments list.
     */
    public function toArray(): array
    {
        return SegmentsList::fromPath($this)->toArray();
    }

    /**********
     * Changes
     */

    /**
     * Create a new object with appended sub path.
     * @param string $subPath Sub path to be appended.
     * @return self Appended path.
     */
    public function append(string $subPath): self
    {
        if ($subPath === '' || $subPath === '/') {
            return $this;
        }

        $left  = $this->value;
        $right = $subPath;
        if (mb_substr($this->value, -1) === '/') {
            $left = mb_substr($this->value, 0, -1);
        }
        if (mb_substr($subPath, 0, 1) === '/') {
            $right = mb_substr($subPath, 1);
        }
        return new self($left.'/'.$right);
    }

    /**
     * Merge paths according to the RFC 3986.
     * @see https://tools.ietf.org/html/rfc3986#section-5.2.3
     * @param  Path $path The path to be merged.
     * @return self       Merged paths.
     */
    public function merge(Path $path): self
    {
        if ($path->isEmpty()) {
            return $this;
        }

        if ($path->isAbsolute()) {
            return $path->resolve();
        }

        return $this->removeBase()->append($path)->resolve();
    }

    /**
     * Create a new object with the path with the last segment of the base path removed.
     * @see https://tools.ietf.org/html/rfc3986#section-5.2.3
     * @return self Path with the last segment of the base path removed.
     */
    public function removeBase(): self
    {
        if ($this->value==='' || $this->value==='/') {
            return $this;
        }
        if (in_array(mb_strrchr($this->value, '/'), ['/','/.','/..'])) {
            return $this;
        }
        $lastSlashPosition = mb_strrpos($this->value, '/');
        return new self(mb_substr($this->value, 0, $lastSlashPosition+1));
    }

    /**
     * Go one or more levels up.
     * By default, the path is resolved before being leveled up.
     * By default, empty segments or duplicated trailing slashes aren't removed.
     * @param int  $levelsNumber        The number of levels.
     * @param bool $resolve             Should resolve path before leveling up?
     * @param bool $removeEmptySegments Should remove empty segments before leveling up?
     * @return self
     */
    public function levelUp(
        int $levelsNumber = 1,
        bool $resolve = true,
        bool $removeEmptySegments = false
    ): self {
        if ($levelsNumber <= 0) {
            return $this;
        }

        $segments = SegmentsList::fromPath($this)->levelUp(
            $levelsNumber,
            $resolve,
            $removeEmptySegments
        );
        $path = clone $this;
        $path->value = join('/', $segments->toArray()) . '/';
        if ($path->value !== '/' && mb_substr($this->value, 0, 1) === '/') {
            $path->value = '/'.$path;
        }
        return $path;
    }

    /**
     * Remove the dot-segments resolving the path according to RFC 3986.
     * @see https://tools.ietf.org/html/rfc3986#section-5.2.4
     * @param bool $removeEmptySegments Should remove empty segments (ie:duplicated slashes)?
     * @return self
     */
    public function resolve(bool $removeEmptySegments = false): self
    {
        $path = clone $this;
        $path->value = join(
            '/',
            SegmentsList::fromPath($this)->resolve($removeEmptySegments)->toArray()
        );
        if (mb_substr($this->value, 0, 1)==='/') {
            $path->value= '/'.$path;
        }
        if ($path->value === '/') {
            return $path;
        }
        if ($this->isDirectory()) {
            $path->value .= '/';
        }
        return $path;
    }

    /**********
     * Getters
     */

    /**
     * Check if the path is empty.
     * An empty path is a path with a an empty string.
     * @return bool Is the path empty?
     */
    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    /**
     * Check if the path is absolute.
     * An absolute path starts with a "/".
     * @return bool Is the path absolute?
     */
    public function isAbsolute(): bool
    {
        return mb_substr($this->value, 0, 1) === '/';
    }

    /**
     * Check if the path is a directory.
     * 
     * @return bool Is it a directory?
     */
    public function isDirectory(): bool
    {
        return in_array(mb_strrchr($this->value, '/'), ['/', '/..', '/.']);
    }

    /**
     * Examples:
     *  - The last segment of "/hello/world" is "world".
     *  - The last segment of "/hello/world/" is also "world".
     *  - But the las segment of "/hello/world//" is "".
     * 
     * @return Segment The last segment.
     */
    public function getLastSegment(): Segment
    {
        if ($this->value === '' || $this->value === '/') {
            return new Segment('');
        }
        preg_match('~/?([^/]*)/?$~u', $this->value, $matches);
        return new Segment($matches[1]);
    }

    /**
     * Find the path base name.
     * The base name is the segment on the right side of the right-most slash.
     * Examples:
     * - The "/hello/world" base name is "world".
     * - The "/hello/world/" and "/hello/world/." don't have a base name (an
     *   empty string should be returned).
     * @return self The basename or an empty path.
     */
    public function getBase(): self
    {
        $lastSlashPosition = mb_strrpos($this->value, '/');
        if ($lastSlashPosition === false) {
            return new self;
        }
        $segment = mb_substr($this->value, $lastSlashPosition+1);
        if ($segment === '.' || $segment === '..') {
            return new self;
        }
        return new self($segment);
    }

    /**
     * Find the path directory.
     * The path directory is the path without its basename.
     * Examples:
     * - The "/hello/world" directory is "/hello/".
     * - The "/hello/world/" directory is "/hello/world/".
     * @return self The directory os an empty path.
     */
    public function getDirectory(): self
    {
        if ($this->isDirectory()) {
            return $this;
        }
        $lastSlashPosition = mb_strrpos($this->value, '/');
        if ($lastSlashPosition === false) {
            return new self;
        }
        return new self(mb_substr($this->value, 0, $lastSlashPosition+1));
    }

    /**
     * Find the last segment extension.
     * It the last segment has several extensions, the last one should be
     * returned.
     * Examples:
     *  - If the path is "/hello/world.php", it should be returned "php".
     *  - If the path is "/hello/world.tar.gz", it should be returned "gz".
     *  - If the path is "/hello/.world", it should be returned "".
     * @return string Last segment extension or an empty string.
     */
    public function getExtension(): string
    {
        return $this->getLastSegment()->getExtension();
    }

    /**
     * Find all the last segment extensions.
     * Examples:
     *  - If the path is "/hello/world.php", it should be returned ["php"].
     *  - If the path is "/hello/world.tar.gz", it should be returned ["tar", "gz"].
     *  - If the path is "/hello/.world", it should be returned [].
     * @return string[] All last segment extensions.
     */
    public function getAllExtensions(): array
    {
        return $this->getLastSegment()->getAllExtensions();
    }
}
