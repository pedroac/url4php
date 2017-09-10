<?php
/**
 * Schemes/ports mapping.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @version 0.3.1
 */
namespace pedroac\url;

class SchemesPortsMap
{
    /**
     * Schemes/ports mapping.
     * The keys are schemes, the values are ports.
     * @var array
     */
    private $map;

    /**
     * Default instance.
     * @var self
     */
    static private $defaultInstance;

    /**
     * Create a new schemes/port map.
     * @param array $map Associative array. The keys should be schemes, the values
     *                   should be ports numbers.
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * Return default instance, with default schemes/ports mapping.
     * @return self
     */
    static public function default(): self
    {
        if (!self::$defaultInstance) {
            self::$defaultInstance = self::fromFile(__DIR__.'/../data/schemes.csv');
        }
        return self::$defaultInstance;
    }

    /**
     * Create instance from file.
     * @param string $filePath
     * @return self
     */
    static public function fromFile(string $filePath): self
    {
        return new self(self::loadSchemesPortsMap($filePath));
    }

    /**
     * Check if a specified schemes is mapped to a specified port.
     * @param string $scheme Scheme
     * @param int $port Port
     * @return bool Is $scheme mapped to $port?
     */
    public function isSchemePort(string $scheme, int $port): bool
    {
        $scheme = strtolower($scheme);
        if (!isset($this->map[$scheme])) {
            return false;
        }
        return $this->map[$scheme] == $port;
    }

    /**
     * Load a CSV file with the schemes and ports mapping.
     * @param  string $filePath CSV file path with the schemes/ports mapping.
     * @return array            The keys should be schemes.
     *                          The values should be the related default ports.
     * @codeCoverageIgnore
     */
    static private function loadSchemesPortsMap(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException(sprintf('Couldn\'t load the file %s: %s.',
                                                $filePath,
                                                error_get_last()));
        }

        $map = array();
        while (($fields = fgetcsv($handle)) !== false) {
            if (!isset($fields[1])) {
                throw new \RuntimeException(sprintf('Invalid schemes/port mapping file %s.',
                                                    $filePath));
            }
            $scheme = strtolower(trim($fields[0]));
            $map[$scheme] = (int)trim($fields[1]);
        }
        return $map;
    }
}