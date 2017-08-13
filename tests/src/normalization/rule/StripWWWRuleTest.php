<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\Parsed;
use pedroac\url\URL;

class StripWWWRuleTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\rule\StripWWWRule::apply
     */
    public function testApply()
    {
        $rule = new StripWWWRule;

        $components1 = (new Parsed(new URL('http://www.test.com')))->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://test.com', (string)$components1);

        $components2 = (new Parsed(new URL('http://test.com')))->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://test.com', $components2);

        $components3 = (new Parsed(new URL('test.com')))->toComponents();
        $rule->apply($components3);
        $this->assertEquals('test.com', (string)$components3);

        $components4 = (new Parsed(new URL('http://pedr.oac:qw.erty@www.test.com:90')))
                        ->toComponents();
        $rule->apply($components4);
        $this->assertEquals('http://pedr.oac:qw.erty@test.com:90', (string)$components4);

        $components5 = (new Parsed(new URL('http://test.www.com:90')))->toComponents();
        $rule->apply($components5);
        $this->assertEquals('http://test.www.com:90', (string)$components5);
    }
}
