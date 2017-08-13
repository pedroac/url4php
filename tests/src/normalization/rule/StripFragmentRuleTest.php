<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\Parsed;
use pedroac\url\URL;

class StripFragmentRuleTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\rule\StripFragmentRule::apply
     */
    public function testApply()
    {
        $rule = new StripFragmentRule;

        $components1 = (new Parsed(new URL('http://test.com#label')))
                        ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://test.com', (string) $components1);

        $components2 = (new Parsed(new URL('http://test.com#')))
                        ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://test.com', (string) $components2);

        $components3 = (new Parsed(new URL('http://test.com')))
                        ->toComponents();
        $rule->apply($components3);
        $this->assertEquals('http://test.com', (string) $components3);
    }
}
