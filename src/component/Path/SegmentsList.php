<?php
/**
 * SegmentsList class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.4.0
 */
namespace pedroac\url\component\Path;

use pedroac\url\component\Path;

/**
 * Path segments list.
 * It should be immutable.
 */
class SegmentsList
{
    /**
     * An array of path segments.
     *
     * @var Segment[]
     */
    private $segments = [];

    /**
     * Create an immutable segments list.
     *
     * @param Segment[] $segments
     */
    public function __construct(array $segments = [])
    {
        foreach ($segments as $segment) {
            $this->segments []= new Segment($segment);
        }
    }

    /**
     * Create segments list from path.
     *
     * @param Path $path The path.
     * @return self
     */
    public static function fromPath(Path $path): self
    {
        $pathString = (string)$path;
        if ($pathString === '' || $pathString === '/') {
            return new self([]);
        }
        if ($pathString === '//') {
            return new self(['']);
        }

        $offset = 0;
        $length = null;
        if (mb_substr($pathString, 0, 1) === '/') {
            $offset = 1;
        }
        if (mb_substr($pathString, -1) === '/') {
            $length = -1;
        }
        return new self(mb_split('[/]', mb_substr($pathString, $offset, $length)));
    }

    /**
     * Convert to a path string without the start and ending slashes.
     *
     * @return string Path string.
     */
    public function __toString(): string
    {
        return join('/', $this->segments);
    }

    /**
     * Return the path segments array.
     *
     * @return Segment[]
     */
    public function toArray(): array
    {
        return $this->segments;
    }

    /**
     * Remove the dot-segments resolving the associated path according to RFC 3986.
     * @param  bool  $removeEmptySegments Should remove empty segments?
     * @return self The resolved path segments.
     */
    public function resolve(bool $removeEmptySegments = false): self
    {
        $newInstance = new self;
        foreach ($this->segments as $segment) {
            if ($segment == '.' || ($segment == '' && $removeEmptySegments)) {
                continue;
            } elseif ($segment == '..') {
                array_pop($newInstance->segments);
                continue;
            }
            $newInstance->segments []= $segment;
        }
        return $newInstance;
    }

    /**
     * Go one or more levels up.
     * By default, the path is resolved before being leveled up.
     * By default, empty segments aren't removed.
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

        $newInstance = $resolve
            ? $this->resolve($removeEmptySegments)
            : clone $this;
        for ($level = 0; $level < $levelsNumber; ++$level) {
            if (array_pop($newInstance->segments) === null) {
                break;
            }
        }
        return $newInstance;
    }
}
