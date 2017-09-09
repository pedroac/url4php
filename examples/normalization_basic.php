<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\URL;
use pedroac\url\normalization\Rules;

$rules = Rules::basic();
echo $rules->apply(new URL('HttP://TesT.com/my/.//path?b=1&c=2&a=3')), "\n";
