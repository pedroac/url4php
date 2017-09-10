<?php
/**
 * Segments class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.4.0
 */
namespace pedroac\url\component\Path;

use pedroac\url\component\Path;

/**
 * The path segment value object.
 * It should be immutable.
 */
class Segment
{
    /**
     * Path segment.
     *
     * @var string
     */
    private $value;

    /**
     * Create a new path segment value object.
     *
     * @param string $value Path segment.
     */
    public function __construct(string $value = '')
    {
        if (strpos($value, '/') !== false) {
            throw new \DomainException('Path segment may not have slashes: ' . $value);
        }
        $this->value = trim($value, '/');
    }

    /**
     * Return the path segment string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * Find the extension.
     * It the last segment has several extensions, the last one should be
     * returned.
     * Examples:
     *  - If the path is "world.php", it should be returned "php".
     *  - If the path is "world.tar.gz", it should be returned "gz".
     *  - If the path is ".world", it should be returned "".
     *
     * @return string The extension or an empty string.
     */
    public function getExtension()
    {
        if ($this->value === '') {
            return '';
        }

        $position = mb_strrpos($this->value, '.');
        if (!$position) {
            return '';
        }
        return mb_substr($this->value, $position+1);
    }

    /**
     * Find all extensions.
     * Examples:
     *  - If the path is "world.php", it should be returned ["php"].
     *  - If the path is "world.tar.gz", it should be returned ["tar", "gz"].
     *  - If the path is ".world", it should be returned [].
     * 
     * @return string[] All extensions.
     */
    public function getAllExtensions()
    {
        if ($this->value === '') {
            return [];
        }

        $position = mb_strpos($this->value, '.');
        if (!$position) {
            return [];
        }
        $extensionsStr = mb_substr($this->value, $position+1);
        return mb_split('[.]', $extensionsStr);
    }
}