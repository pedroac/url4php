<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\URL;
use pedroac\url\normalization\Rules;
use pedroac\url\normalization\rule\StripUnusedParametersRule;
use pedroac\url\normalization\rule\StripDefaultParametersRule;

$rules = Rules::basicAnd(
    new StripUnusedParametersRule(['a','b','c']),
    new StripDefaultParametersRule(['b'=>2])
);
echo $rules->apply(new URL('HttP://TesT.com/my/.//path?b=2&c=2&a=3')), "\n";
