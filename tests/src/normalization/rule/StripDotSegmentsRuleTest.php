<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\Parsed;
use pedroac\url\URL;

class StripDotSegmentsRuleTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\rule\StripDotSegmentsRule::apply
     */
    public function testApply()
    {
        $rule = new StripDotSegmentsRule;

        $components1 = (new Parsed(new URL('http://test.com/./abc/def/../ghi/')))
                       ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://test.com/abc/ghi/', $components1);
    }
}
