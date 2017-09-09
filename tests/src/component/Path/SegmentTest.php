<?php
namespace pedroac\url\component\Path;

use PHPUnit\Framework\TestCase;

class SegmentTest extends TestCase
{
    /**
     * @covers pedroac\url\component\Path\Segment::__construct
     */
    public function testConstructorException()
    {
        $this->expectException(\DomainException::class);
        new Segment('aa/bb/file.ext');
    }

    public function testToString()
    {
        $this->assertEquals('file.ext', (string)new Segment('file.ext'));
    }

    /**
     * @covers pedroac\url\component\Path\Segment::__toString
     * @covers pedroac\url\component\Path\Segment::__construct
     * @covers pedroac\url\component\Path\Segment::getExtension
     */
    public function testGetExtension()
    {
        $this->assertEquals('ext', (new Segment('file.ext'))->getExtension());
        $this->assertEquals('ext', (new Segment('file.prv.ext'))->getExtension());
        $this->assertEquals('ext', (new Segment('.file.ext'))->getExtension(), '.file.ext');
        $this->assertEquals('', (new Segment('.ext'))->getExtension());
        $this->assertEquals('', (new Segment())->getExtension());
    }

     /**
     * @covers pedroac\url\component\Path\Segment::__toString
     * @covers pedroac\url\component\Path\Segment::__construct
     * @covers pedroac\url\component\Path\Segment::getAllExtensions
     */
    public function testGetAllExtensions()
    {
        $this->assertEquals(['ext'], (new Segment('file.ext'))->getAllExtensions());
        $this->assertEquals(['prv', 'ext'], (new Segment('file.prv.ext'))->getAllExtensions());
        $this->assertEquals([], (new Segment('file'))->getAllExtensions());
        $this->assertEquals([], (new Segment('.file'))->getAllExtensions());
        $this->assertEquals([], (new Segment())->getAllExtensions());
    }
}
