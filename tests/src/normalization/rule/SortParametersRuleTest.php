<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\URL;

class SortParametersRuleTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\rule\SortParametersRule::apply
     */
    public function testApply()
    {
        $rule = new SortParametersRule;

        $components = (new URL('http://test.com/path/?c=1&b=3&a=2'))
                      ->parse()->toComponents();
        $rule->apply($components);
        $this->assertEquals('http://test.com/path/?a=2&b=3&c=1', (string)$components);

        $components = (new URL('http://test.com/path/?b[e]=3&b[d]=4'))
                      ->parse()->toComponents();
        $rule->apply($components);
        $this->assertEquals('http://test.com/path/?b%5Bd%5D=4&b%5Be%5D=3',
                            (string)$components);
    }
}
