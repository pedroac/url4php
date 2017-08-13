<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\Parsed;
use pedroac\url\URL;

class StripUnusedParametersRuleTest extends TestCase
{

    /**
     * @covers pedroac\url\normalization\rule\StripUnusedParametersRule::apply
     * @covers pedroac\url\normalization\rule\StripUnusedParametersRule::__construct
     */
    public function testApply()
    {
        $rule = new StripUnusedParametersRule(['b', 'd', 'f']);

        $components1 = (new Parsed(new URL('http://test.com?a=1&b=2')))
                        ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://test.com?b=2', (string)$components1);

        $components2 = (new Parsed(new URL('http://test.com?a=1&b=2&c=3&d=4&e=5&f=6&g=7')))
                        ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://test.com?b=2&d=4&f=6', (string)$components2);
    }
}
