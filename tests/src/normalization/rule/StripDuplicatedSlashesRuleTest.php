<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\Parsed;
use pedroac\url\URL;

class StripDuplicatedSlashesRuleTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\rule\StripDuplicatedSlashesRule::apply
     */
    public function testApply()
    {
        $rule = new StripDuplicatedSlashesRule;

        $components1 = (new Parsed(new URL('http://test.com///abc//def/////fgh/hij')))
                       ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://test.com/abc/def/fgh/hij',
                            (string) $components1);
    }
}
