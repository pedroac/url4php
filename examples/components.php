<?php
require __DIR__ . '/../vendor/autoload.php';

use pedroac\url\URL;
use pedroac\url\component\Scheme;
use pedroac\url\component\Query;
use pedroac\url\component\Path;

$url        = new URL('http://test.com/my/path');
$components = $url->parse()->toComponents();
$components->scheme = new Scheme('https');
$components->query  = Query::fromArray(['a' => '1']);
$components->path   = $components->getPath()
                      ->merge(new Path('new/path'));
$newUrl = new URL($components);
echo $newUrl, "\n";
