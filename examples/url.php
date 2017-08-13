<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\URL;
use pedroac\url\component\Scheme;
use pedroac\url\component\Query;
use pedroac\url\component\Fragment;

$url = new URL('http://test.com/my/path');
$newUrl = $url->with(new Scheme('https'),
                     null,
                     null,
                     Query::fromArray(['a' => 1, 'b' => 2]),
                     new Fragment('label'));
echo $newUrl, "\n";
echo  $newUrl->changeParameters(['c' => 3]), "\n";
