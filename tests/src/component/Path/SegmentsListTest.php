<?php
namespace pedroac\url\component\Path;

use PHPUnit\Framework\TestCase;
use pedroac\url\component\Path;

class SegmentsListTest extends TestCase
{
    /**
     * @covers pedroac\url\component\Path\SegmentsList::fromPath
     * @covers pedroac\url\component\Path\SegmentsList::__construct
     */
    public function testFromPath()
    {
        $this->assertEquals(
            new SegmentsList([]),
            SegmentsList::fromPath(new Path('/'))
        );
        $this->assertEquals(
            new SegmentsList(['']),
            SegmentsList::fromPath(new Path('//'))
        );
        $this->assertEquals(
            new SegmentsList([new Segment('a')]),
            SegmentsList::fromPath(new Path('/a'))
        );
        $this->assertEquals(
            new SegmentsList([new Segment('a'), new Segment('b')]),
            SegmentsList::fromPath(new Path('/a/b'))
        );
        $this->assertEquals(
            new SegmentsList([new Segment('a')]),
            SegmentsList::fromPath(new Path('a/'))
        );
        $this->assertEquals(
            new SegmentsList([new Segment('a'), new Segment('b')]),
            SegmentsList::fromPath(new Path('a/b/'))
        );
        $this->assertEquals(
            new SegmentsList([new Segment('a')]),
            SegmentsList::fromPath(new Path('a'))
        );
        $this->assertEquals(
            new SegmentsList([new Segment('a'), new Segment('b')]),
            SegmentsList::fromPath(new Path('a/b'))
        );
    }

    /**
     * @covers pedroac\url\component\Path\SegmentsList::fromPath
     * @covers pedroac\url\component\Path\SegmentsList::toArray
     */
    public function testToArray()
    {
        $this->assertEquals(
            [],
            SegmentsList::fromPath(new Path('/'))->toArray()
        );
        $this->assertEquals(
            [new Segment('')],
            SegmentsList::fromPath(new Path('//'))->toArray()
        );
        $this->assertEquals(
            [new Segment('a')],
            SegmentsList::fromPath(new Path('/a'))->toArray()
        );
        $this->assertEquals(
            [new Segment('a'), new Segment('b')],
            SegmentsList::fromPath(new Path('/a/b'))->toArray()
        );
        $this->assertEquals(
            [new Segment('a')],
            SegmentsList::fromPath(new Path('a/'))->toArray()
        );
        $this->assertEquals(
            [new Segment('a'), new Segment('b')],
            SegmentsList::fromPath(new Path('a/b/'))->toArray()
        );
        $this->assertEquals(
            [new Segment('a')],
            SegmentsList::fromPath(new Path('a'))->toArray()
        );
        $this->assertEquals(
            [new Segment('a'), new Segment('b')],
            SegmentsList::fromPath(new Path('a/b'))->toArray()
        );
    }

    /**
     * @covers pedroac\url\component\Path\SegmentsList::resolve
     * @covers pedroac\url\component\Path\SegmentsList::fromPath
     * @covers pedroac\url\component\Path\SegmentsList::__toString
     */
    public function testResolve()
    {
        $this->assertEquals('',        (string)SegmentsList::fromPath(new Path('/'))->resolve());
        $this->assertEquals('a',       (string)SegmentsList::fromPath(new Path('/a'))->resolve());
        $this->assertEquals('a/b/c',   (string)SegmentsList::fromPath(new Path('a/b/c/d/..'))->resolve());
        $this->assertEquals('a/b',     (string)SegmentsList::fromPath(new Path('/a/b/c/d/../..'))->resolve());
        $this->assertEquals('a/d',     (string)SegmentsList::fromPath(new Path('a/b/../d'))->resolve());
        $this->assertEquals('a/b/c/d', (string)SegmentsList::fromPath(new Path('./a/b/c/d'))->resolve());
        $this->assertEquals('a/b/c/d', (string)SegmentsList::fromPath(new Path('/a/b/./c/d'))->resolve());
        $this->assertEquals('a/b/c/d', (string)SegmentsList::fromPath(new Path('././a/b/c/d'))->resolve());
        $this->assertEquals('a/d',     (string)SegmentsList::fromPath(new Path('././a/b/../c/../d/./'))->resolve());
    }

    /**
     * @covers pedroac\url\component\Path\SegmentsList::levelUp
     * @covers pedroac\url\component\Path\SegmentsList::fromPath
     * @covers pedroac\url\component\Path\SegmentsList::__toString
     */
    public function testLevelUp()
    {
        $this->assertEquals('a/b/c/d', (string)SegmentsList::fromPath(new Path('/a/b/c/d'))->levelUp(0));
        $this->assertEquals('',        (string)SegmentsList::fromPath(new Path('/a/b/'))->levelUp(3));
        $this->assertEquals('a/b/c',   (string)SegmentsList::fromPath(new Path('/a/b/c/d'))->levelUp());
        $this->assertEquals('a/b',     (string)SegmentsList::fromPath(new Path('/a/b/c/d'))->levelUp(2));
        $this->assertEquals('a/b',     (string)SegmentsList::fromPath(new Path('/a/b/c/d/..'))->levelUp());
        $this->assertEquals('a',       (string)SegmentsList::fromPath(new Path('/a/b/c/d/../..'))->levelUp());
        $this->assertEquals('a',       (string)SegmentsList::fromPath(new Path('/a/b/c/d/..'))->levelUp(2));
    }
}
