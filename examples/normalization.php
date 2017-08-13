<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\URL;
use pedroac\url\normalization\Rules;
use pedroac\url\normalization\rule\StripWWWRule;
use pedroac\url\normalization\rule\StripDuplicatedSlashesRule;
use pedroac\url\normalization\rule\StripDotSegmentsRule;
use pedroac\url\normalization\rule\StripDirectoryIndexRule;
use pedroac\url\normalization\rule\StripDefaultPortRule;
use pedroac\url\normalization\rule\StripUnusedParametersRule;
use pedroac\url\normalization\rule\StripDefaultParametersRule;

$rules = new Rules(new StripWWWRule,
                   new StripDuplicatedSlashesRule,
                   new StripDotSegmentsRule,
                   new StripDirectoryIndexRule,
                   new StripDefaultPortRule,
                   new StripUnusedParametersRule(['a','b','c']),
                   new StripDefaultParametersRule(['b'=>2])
                   );
echo $rules->apply(new URL('HTTP://Test.Com/my/path?a=1&b=2&f=3')), "\n";
