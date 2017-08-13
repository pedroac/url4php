<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\URL;

class AddTrailingRuleTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\rule\AddTrailingRule::apply
     */
    public function testApply()
    {
        $components = (new URL('http://test.com/path'))->parse()->toComponents();
        $rule = new AddTrailingRule;
        $rule->apply($components);
        $this->assertEquals('http://test.com/path/', (string)$components);
    }
}
