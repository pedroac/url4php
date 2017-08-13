<?php

namespace pedroac\url\normalization\rule;

use PHPUnit\Framework\TestCase;
use pedroac\url\Parsed;
use pedroac\url\URL;

class StripDirectoryIndexRuleTest extends TestCase
{

    /**
     * @covers pedroac\url\normalization\rule\StripDirectoryIndexRule::fromFile
     * covers pedroac\url\normalization\rule\StripDirectoryIndexRule::__construct
     */
    public function testFromFile()
    {
        $rule = StripDirectoryIndexRule::fromFile(__DIR__ . '/../../../data/indexes.csv');

        $components1 = (new Parsed(new URL('http://host.com/index.php')))
                       ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://host.com/', (string) $components1);

        $components2 = (new Parsed(new URL('http://host.com/index.php?a=1&b=2#label')))
                ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://host.com/?a=1&b=2#label', (string) $components2);

        $components3 = (new Parsed(new URL('index.php')))
                       ->toComponents();
        $rule->apply($components3);
        $this->assertEquals('', (string) $components3);

        $components4 = (new Parsed(new URL('fake.idx')))
                       ->toComponents();
        $rule->apply($components4);
        $this->assertEquals('fake.idx', (string) $components4);
    }

    /**
     * @covers pedroac\url\normalization\rule\StripDirectoryIndexRule::apply
     * @covers pedroac\url\normalization\rule\StripDirectoryIndexRule::__construct
     */
    public function testApply()
    {
        $rule = new StripDirectoryIndexRule(['index.php', 'index.html']);

        $components1 = (new Parsed(new URL('http://host.com/index.php')))
                       ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://host.com/', $components1);

        $components2 = (new Parsed(new URL('http://host.com/index.php?a=1&b=2#label')))
                       ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://host.com/?a=1&b=2#label', $components2);

        $components3 = (new Parsed(new URL('index.php')))
                       ->toComponents();
        $rule->apply($components3);
        $this->assertEquals('', $components3);

        $components4 = (new Parsed(new URL('index.jsp')))
                       ->toComponents();
        $rule->apply($components4);
        $this->assertEquals('index.jsp', $components4);
    }

    /**
     * @covers pedroac\url\normalization\rule\StripDirectoryIndexRule::apply
     * @covers pedroac\url\normalization\rule\StripDirectoryIndexRule::__construct
     */
    public function testApply2()
    {
        $rule = new StripDirectoryIndexRule(['index.php', 'index.html']);

        $components1 = (new Parsed(new URL('http://host.com/index.php')))
                       ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://host.com/', (string) $components1);

        $components2 = (new Parsed(new URL('http://host.com/index.php?a=1&b=2#label')))
                       ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://host.com/?a=1&b=2#label', (string) $components2);

        $components3 = (new Parsed(new URL('index.php')))
                       ->toComponents();
        $rule->apply($components3);
        $this->assertEquals('', (string) $components3);

        $components4 = (new Parsed(new URL('fake.idx')))
                       ->toComponents();
        $rule->apply($components4);
        $this->assertEquals('fake.idx', (string) $components4);
    }

     /**
     * @covers pedroac\url\normalization\rule\StripDirectoryIndexRule::apply
     * @covers pedroac\url\normalization\rule\StripDirectoryIndexRule::__construct
     */
    public function testApply3()
    {
        $rule = new StripDirectoryIndexRule();

        $components1 = (new Parsed(new URL('http://host.com/index.php')))
                       ->toComponents();
        $rule->apply($components1);
        $this->assertEquals('http://host.com/', (string) $components1);

        $components2 = (new Parsed(new URL('http://host.com/index.php?a=1&b=2#label')))
                       ->toComponents();
        $rule->apply($components2);
        $this->assertEquals('http://host.com/?a=1&b=2#label', (string) $components2);

        $components3 = (new Parsed(new URL('index.php')))
                       ->toComponents();
        $rule->apply($components3);
        $this->assertEquals('', (string) $components3);

        $components4 = (new Parsed(new URL('fake.idx')))
                       ->toComponents();
        $rule->apply($components4);
        $this->assertEquals('fake.idx', (string) $components4);
    }
}
