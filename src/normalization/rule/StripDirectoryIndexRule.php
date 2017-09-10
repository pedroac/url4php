<?php
/**
 * StripDirectoryIndexRule class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization\rule;

use pedroac\url\Components;
use pedroac\url\component\Path;

/**
 * Removing the default port normalization rule.
 * @see https://en.wikipedia.org/wiki/URL_normalization#Normalizations_that_change_semantics
 */
class StripDirectoryIndexRule implements Rule
{
    /**
     * List of the default index directories.
     * It should be filled the first time it's required.
     * @var string[]
     */
    public static $defaultIndexDirs;

    /**
     * List of index directories.
     * @var string[]
     */
    private $indexDirectories;

    /**
     * Create a new normalization rule.
     * If no arguments are provided, the default index directories list should
     * be used.
     * @param string[] $indexDirectories Index directories list.
     */
    public function __construct(array $indexDirectories = null)
    {
        $this->indexDirectories = $indexDirectories;
        if ($indexDirectories === null) {
            $this->loadDefaultFile();
        }
    }

    /**
     * Load default file.
     * @return void
     * @codeCoverageIgnore
     */
    private function loadDefaultFile()
    {
        if (!self::$defaultIndexDirs) {
            self::$defaultIndexDirs = self::loadIndexes(__DIR__ . '/../../../data/indexes.csv');
        }
        $this->indexDirectories = self::$defaultIndexDirs;
    }

    /**
     * Create a new object with an index directories list loaded from a file.
     * Each line should have an index directory, eg:
     * """
     * index.php
     * index.html
     * """
     * @param string $filePath A file path or URL.
     * @return self
     */
    public static function fromFile(string $filePath): self
    {
        return new self(self::loadIndexes($filePath));
    }

    /**
     * Load index directories from a file.
     * @param  string $filePath File path.
     * @return string[]         List of index directories.
     * @codeCoverageIgnore
     */
    private static function loadIndexes(string $filePath): array
    {
        $indexes = file($filePath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        if (!is_array($indexes)) {
            throw new \RuntimeException(sprintf('Couldn\t open file %s.',
                                                $indexes,
                                                error_get_last()));
        }
        return $indexes;
    }

    /**
     * Remove the default index directory (eg: "index.php"), or the base segment
     * if it's among a specified list.
     * @param Components $components
     */
    public function apply(Components $components): void
    {
        $base = $components->getPath()->getLastSegment();
        if ($base !== '' && in_array($base, $this->indexDirectories)) {
            $pathString = mb_strrchr($components->getPath(), $base, true);
            $components->setPath(new Path($pathString));
        }
    }
}
