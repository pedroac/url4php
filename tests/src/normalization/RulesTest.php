<?php

namespace pedroac\url\normalization;

use pedroac\url\normalization\rule\StripWWWRule;
use pedroac\url\normalization\rule\AddTrailingRule;
use pedroac\url\normalization\rule\StripFragmentRule;
use PHPUnit\Framework\TestCase;
use pedroac\url\URL;

class RulesTest extends TestCase
{
    /**
     * @covers pedroac\url\normalization\Rules::apply
     * @covers pedroac\url\normalization\Rules::__construct
     */
    public function testApply()
    {
        $rules = new Rules(new StripWWWRule,
                           new AddTrailingRule);
        $this->assertEquals('http://test.com/path/',
                            $rules->apply(new URL('http://www.test.com/path')));
    }

    /**
     * @covers pedroac\url\normalization\Rules::safeAnd
     * @covers pedroac\url\normalization\Rules::__construct
     */
    public function testSafeAnd()
    {
        $rules = Rules::safeAnd(new StripWWWRule,
                                new AddTrailingRule);
        $this->assertEquals('http://test.com/path/',
                            $rules->apply(new URL('http://www.test.com:80/path')));
    }

    /**
     * @covers pedroac\url\normalization\Rules::safe
     * @covers pedroac\url\normalization\Rules::__construct
     */
    public function testSafe()
    {
        $rules = Rules::safe();
        $this->assertEquals('http://www.test.com/path',
                            $rules->apply(new URL('http://www.test.com:80/path')));
    }

    /**
     * @covers pedroac\url\normalization\Rules::basicAnd
     * @covers pedroac\url\normalization\Rules::__construct
     */
    public function testBasicAnd()
    {
        $rules = Rules::basicAnd(new StripFragmentRule);
        $this->assertEquals('http://test.com/a/b/',
                            $rules->apply(
                                new URL('http://www.test.com:80/a//b/c/../index.php#label')
                            )
                           );
    }

    /**
     * @covers pedroac\url\normalization\Rules::basic
     * @covers pedroac\url\normalization\Rules::__construct
     */
    public function testBasic()
    {
        $rules = Rules::basic();
        $this->assertEquals('http://test.com/a/b/',
                            $rules->apply(
                                new URL('http://www.test.com:80/a//b/c/../index.php')
                            )
                           );
    }
}
