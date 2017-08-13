<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\URL;

$url = new URL('http://test.com/my/path');
var_dump($url->isAbsolute());
