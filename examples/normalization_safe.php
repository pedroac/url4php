<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\URL;
use pedroac\url\normalization\Rules;

$rules = Rules::safe();
echo $rules->apply(new URL('HttP://TesT.com/my/path?a=1&b=2&f=3')), "\n";
