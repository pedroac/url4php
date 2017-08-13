<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\URL;

class ReplaceSchemesRuleTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\rule\ReplaceSchemesRule::apply
     * @covers pedroac\url\normalization\rule\ReplaceSchemesRule::__construct
     */
    public function testApply()
    {
        $rule = new ReplaceSchemesRule;

        $components = (new URL('http://test.com/path'))->parse()->toComponents();
        $rule->apply($components);
        $this->assertEquals('https://test.com/path', (string)$components);
    }
}
