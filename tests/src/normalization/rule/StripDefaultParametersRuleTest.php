<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\Parsed;
use pedroac\url\URL;

class StripDefaultParametersRuleTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\rule\StripDefaultParametersRule::apply
     * @covers pedroac\url\normalization\rule\StripDefaultParametersRule::__construct
     */
    public function testApply()
    {
        $rule = new StripDefaultParametersRule(['a' => 1, 'b' => 2, 'c' => 3]);

        $components1 = (new Parsed(new URL('?a=1&b=2&c=3')))->toComponents();
        $rule->apply($components1);
        $this->assertEquals('', (string) $components1);

        $components2 = (new Parsed(new URL('?a=1&b=3&c=4')))->toComponents();
        $rule->apply($components2);
        $this->assertEquals('?b=3&c=4', (string) $components2);

        $components3 = (new Parsed(new URL('?d=ttt&a=1')))->toComponents();
        $rule->apply($components3);
        $this->assertEquals('?d=ttt', (string) $components3);
    }
}
