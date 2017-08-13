<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\URL;
use pedroac\url\normalization\Rules;
use pedroac\url\normalization\rule\StripUnusedParametersRule;
use pedroac\url\normalization\rule\StripDefaultParametersRule;

$rules = Rules::basicAnd(new StripDefaultParametersRule(['b'=>2]),
                         new StripUnusedParametersRule(['a','b']));
echo $rules->apply(new URL('http://test.com/my/path?a=1&b=2&f=3')), "\n";
