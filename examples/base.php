<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\Base;
use pedroac\url\URL;

$base = Base::fromString('http://test.com/this/is');
$newUrl = $base->toAbsolute(new URL('my/path'));
echo $newUrl, "\n";
