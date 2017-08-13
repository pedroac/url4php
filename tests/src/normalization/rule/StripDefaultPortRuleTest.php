<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\Parsed;
use pedroac\url\URL;

class StripDefaultPortRuleTest extends TestCase
{

    /**
     * @covers pedroac\url\normalization\rule\StripDefaultPortRule::fromFile
     * @covers pedroac\url\normalization\rule\StripDefaultPortRule::__construct
     */
    public function testFromFile()
    {
        $rule = StripDefaultPortRule::fromFile(__DIR__ . '/../../../data/schemes.csv');

        $components = (new Parsed(new URL('http://host.com:80')))
                      ->toComponents();
        $rule->apply($components);
        $this->assertEquals('http://host.com', (string)$components);

        $components1 = (new Parsed(new URL('http://host.com:90')))
                      ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://host.com:90', (string)$components1);

        $components2 = (new Parsed(new URL('http://host.com')))
                      ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://host.com', (string)$components2);
    }

    /**
     * @covers pedroac\url\normalization\rule\StripDefaultPortRule::apply
     * @covers pedroac\url\normalization\rule\StripDefaultPortRule::__construct
     */
    public function testApply()
    {
        $rule = new StripDefaultPortRule(['http' => 80]);

        $components = (new Parsed(new URL('http://host.com:80')))
                      ->toComponents();
        $rule->apply($components);
        $this->assertEquals('http://host.com', (string)$components);

        $components1 = (new Parsed(new URL('http://host.com:90')))
                       ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://host.com:90', (string)$components1);

        $components2 = (new Parsed(new URL('http://host.com')))
                       ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://host.com', (string)$components2);
    }

    /**
     * @covers pedroac\url\normalization\rule\StripDefaultPortRule::apply
     * @covers pedroac\url\normalization\rule\StripDefaultPortRule::__construct
     */
    public function testApply2()
    {
        $rule = new StripDefaultPortRule;

        $components = (new Parsed(new URL('http://host.com:80')))
                      ->toComponents();
        $rule->apply($components);
        $this->assertEquals('http://host.com', (string)$components);

        $components1 = (new Parsed(new URL('http://host.com:90')))
                      ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://host.com:90', (string)$components1);

        $components2 = (new Parsed(new URL('http://host.com')))
                      ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://host.com', (string)$components2);

        $components3 = (new Parsed(new URL('rrr://host.com')))
                      ->toComponents();
        $rule->apply($components3);
        $this->assertEquals('rrr://host.com', (string)$components3);
    }
}
