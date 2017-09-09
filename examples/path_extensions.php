<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\Parsed;

$parsed = Parsed::fromString('http://test.com/my/path/file.tar.gz');

echo $parsed->path->getExtension(), "\n";
echo print_r($parsed->path->getAllExtensions());
