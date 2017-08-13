<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\Parsed;

$parsed = Parsed::fromString('http://test.com:80/my/path?a=1&b=2#label');

echo $parsed->scheme   ,"\n";
echo $parsed->host     ,"\n";
echo $parsed->port     ,"\n";
echo $parsed->path     ,"\n";
echo $parsed->query    ,"\n";
echo $parsed->fragment ,"\n";
