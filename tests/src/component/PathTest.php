<?php

namespace pedroac\url\components;

use PHPUnit\Framework\TestCase;
use pedroac\url\component\Path;

class PathTest extends TestCase
{
    /**
     * @covers pedroac\url\component\Path::__construct
     * @covers pedroac\url\component\Path::__toString
     */
    public function testToString()
    {
        $this->assertEquals('/this/is/a/path', (string)new Path('/this/is/a/path'));
        $this->assertEquals('this/is/a/path', (string)new Path('this/is/a/path'));
        $this->assertEquals('/this/is/a/path/..', (string)new Path('/this/is/a/path/..'));
        $this->assertEquals('/', (string)new Path('/'));
        $this->assertEquals('', (string)new Path());
    }

    /**
     * @covers pedroac\url\component\Path::toURIString
     * @todo Test unicode edge cases.
     */
    public function testToURIString()
    {
        $this->assertEquals('/this/is/a/path', (new Path('/this/is/a/path'))->toURIString());
        $this->assertEquals('this/is/a/path', (new Path('this/is/a/path'))->toURIString());
        $this->assertEquals('/this/is/a/path', (new Path('this/is/a/path'))->toURIString(true));
        $this->assertEquals('/this/is/a/path;x', (new Path('/this/is/a/path;x'))->toURIString());
        $this->assertEquals('/this/is/a/path;x=1', (new Path('/this/is/a/path;x=1'))->toURIString());
        $this->assertEquals('/this/is/a;x=1/path;y=2', (new Path('/this/is/a;x=1/path;y=2'))->toURIString());
        $this->assertEquals('this/is/a/path/..', (new Path('this/is/a/path/..'))->toURIString());
        $this->assertEquals('/this/is/a;x=1/../path;y=2', (new Path('/this/is/a;x=1/../path;y=2'))->toURIString());
        $this->assertEquals('/', (new Path('/'))->toURIString());
        $this->assertEquals('', (new Path)->toURIString());

        $this->expectException(\RuntimeException::class);
        (new Path('//a/b/c'))->toURIString();
    }

    /**
     * @covers pedroac\url\component\Path::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertTrue((new Path(''))->isEmpty());
        $this->assertFalse((new Path('/my/path'))->isEmpty());
        $this->assertFalse((new Path('/'))->isEmpty());
    }

    /**
     * @covers pedroac\url\component\Path::isDirectory
     */
    public function testIsDirectory()
    {
        $this->assertTrue((new Path('/hello/world/'))->isDirectory());
        $this->assertTrue((new Path('/hello/world/..'))->isDirectory());
        $this->assertTrue((new Path('/hello/world/.'))->isDirectory());

        $this->assertTrue((new Path('hello/world/'))->isDirectory());
        $this->assertTrue((new Path('hello/world/..'))->isDirectory());
        $this->assertTrue((new Path('hello/world/.'))->isDirectory());

        $this->assertTrue((new Path('./hello/world/'))->isDirectory());
        $this->assertTrue((new Path('../hello/world/..'))->isDirectory());

        $this->assertFalse((new Path('/hello/world'))->isDirectory());
        $this->assertFalse((new Path('/hello/worl/../d'))->isDirectory());
        $this->assertFalse((new Path('/hello/worl/./d'))->isDirectory());
        $this->assertFalse((new Path('./hello/world'))->isDirectory());
        $this->assertFalse((new Path('../hello/world'))->isDirectory());
    }

    /**
     * @covers pedroac\url\component\Path::toArray
     */
    public function testToArray()
    {
        $this->assertEquals(['path'], (new Path('path'))->toArray());
        $this->assertEquals(['a'], (new Path('/a/'))->toArray());
        $this->assertEquals([], (new Path('/'))->toArray());
        $this->assertEquals([''], (new Path('//'))->toArray());
        $this->assertEquals([], (new Path)->toArray());
        $this->assertEquals(['a', 'b', 'c', 'd'], (new Path('/a/b/c/d'))->toArray());
        $this->assertEquals(['a', 'b', 'c', 'd'], (new Path('a/b/c/d'))->toArray());
        $this->assertEquals(['a', 'b', 'c', 'd'], (new Path('a/b/c/d/'))->toArray());
        $this->assertEquals(['a', 'b', 'c', 'd', ''], (new Path('a/b/c/d//'))->toArray());
        $this->assertEquals(['', 'a', 'b', 'c', 'd', ''], (new Path('//a/b/c/d//'))->toArray());
    }

    /**
     * @covers pedroac\url\component\Path::isAbsolute
     */
    public function testIsAbsolute()
    {
        $this->assertTrue((new Path('/my/path'))->isAbsolute());
        $this->assertFalse((new Path('my/path'))->isAbsolute());
        $this->assertFalse((new Path('my'))->isAbsolute());
        $this->assertTrue((new Path('/'))->isAbsolute());
        $this->assertFalse((new Path)->isAbsolute());
        $this->assertFalse((new Path('./my/path'))->isAbsolute());
        $this->assertFalse((new Path('././my/path'))->isAbsolute());
        $this->assertFalse((new Path('../my/path'))->isAbsolute());
    }

    /**
     * @covers pedroac\url\component\Path::append
     */
    public function testAppend()
    {
        $this->assertEquals('/abc/def/', (new Path('/abc/def/'))->append(new Path('/')));
        $this->assertEquals('/abc/def', (new Path('/abc/def'))->append(new Path('/')));
        $this->assertEquals('/abc/def/ghi', (new Path('/abc/def/'))->append(new Path('ghi')));
        $this->assertEquals('/abc/def/ghi', (new Path('/abc/def'))->append(new Path('/ghi')));
        $this->assertEquals('/abc/def/ghi', (new Path('/abc/def'))->append(new Path('ghi')));
        $this->assertEquals('/abc/def/./ghi', (new Path('/abc/def'))->append(new Path('./ghi')));
    }

    /**
     * @covers pedroac\url\component\Path::resolve
     */
    public function testResolve()
    {
        $this->assertEquals('/', (new Path('/../..'))->resolve());
        $this->assertEquals('/', (new Path('/a/b/../..'))->resolve());
        $this->assertEquals('/a/b/c/', (new Path('/a/b/c/d/..'))->resolve());
        $this->assertEquals('/a/b/c/', (new Path('/a/b/c/d/../'))->resolve());
        $this->assertEquals('a/b/c/', (new Path('a/b/c/d/..'))->resolve());
        $this->assertEquals('/a/b/', (new Path('/a/b/c/d/../..'))->resolve());
        $this->assertEquals('/a/d', (new Path('/a/b/../d'))->resolve());
        $this->assertEquals('a/b/c/d', (new Path('./a/b/c/d'))->resolve());
        $this->assertEquals('/a/b/c/d', (new Path('/a/b/./c/d'))->resolve());
        $this->assertEquals('a/b/c/d', (new Path('././a/b/c/d'))->resolve());
        $this->assertEquals('a/d/', (new Path('././a/b/../c/../d/./'))->resolve());
    }

    /**
     * @covers pedroac\url\component\Path::levelUp
     */
    public function testLevelUp()
    {
        $this->assertEquals('/a/b/c/d', (new Path('/a/b/c/d'))->levelUp(0));
        $this->assertEquals('/', (new Path('/a/b/'))->levelUp(3));
        $this->assertEquals('/a/b/c/', (new Path('/a/b/c/d'))->levelUp());
        $this->assertEquals('/a/b/', (new Path('/a/b/c/d'))->levelUp(2));
        $this->assertEquals('/a/b/', (new Path('/a/b/c/d/..'))->levelUp());
        $this->assertEquals('/a/', (new Path('/a/b/c/d/../..'))->levelUp());
        $this->assertEquals('/a/', (new Path('/a/b/c/d/..'))->levelUp(2));
    }

    /**
     * @covers pedroac\url\component\Path::removeBase
     */
    public function testRemoveBase()
    {
        $this->assertEquals('/a/b/c/', (new Path('/a/b/c/d'))->removeBase());
        $this->assertEquals('/a/b/c/d/', (new Path('/a/b/c/d/'))->removeBase());
        $this->assertEquals('/', (new Path('/'))->removeBase());
        $this->assertEquals('', (new Path(''))->removeBase());
    }

    /**
     * @covers pedroac\url\component\Path::merge
     */
    public function testMerge()
    {
        $this->assertEquals('/a/b/c/', (new Path('/a/b/c/'))->merge(new Path()));

        $this->assertEquals('/a/b/c/d', (new Path('/a/b/c/'))->merge(new Path('d')));
        $this->assertEquals('/d', (new Path('/a/b/c/'))->merge(new Path('/d')));
        $this->assertEquals('/a/b/c/d/', (new Path('/a/b/c/'))->merge(new Path('d/')));

        $this->assertEquals('/a/b/d', (new Path('/a/b/c'))->merge(new Path('d')));
        $this->assertEquals('/d', (new Path('/a/b/c'))->merge(new Path('/d')));
        $this->assertEquals('/a/b/d/', (new Path('/a/b/c'))->merge(new Path('d/')));
        $this->assertEquals('/d/', (new Path('/a/b/c'))->merge(new Path('/d/')));
        $this->assertEquals('/a/b/d', (new Path('/a/b/c'))->merge(new Path('./d')));
        $this->assertEquals('/a/b/d/', (new Path('/a/b/c'))->merge(new Path('./d/')));
        $this->assertEquals('/a/d', (new Path('/a/b/c'))->merge(new Path('../d')));
        $this->assertEquals('/d', (new Path('/a/b/c'))->merge(new Path('../../d')));
        $this->assertEquals('/a/d/', (new Path('/a/b/c'))->merge(new Path('../d/')));
        $this->assertEquals('/d/', (new Path('/a/b/c'))->merge(new Path('../../d/')));
    }

    /**
     * @covers pedroac\url\component\Path::getBase
     */
    public function testGetBase()
    {
        $this->assertEquals('world', (new Path('world'))->getBase());
        $this->assertEquals('world', (new Path('/hello/world'))->getBase());
        $this->assertEquals('', (new Path('/hello/world/'))->getBase());
        $this->assertEquals('', (new Path('/'))->getBase());
        $this->assertEquals('', (new Path)->getBase());
        $this->assertEquals('', (new Path('/hello/world/.'))->getBase());
        $this->assertEquals('', (new Path('/hello/world/..'))->getBase());
    }

    /**
     * @covers pedroac\url\component\Path::getDirectory
     */
    public function testGetDirectory()
    {
        $this->assertEquals('/hello/', (new Path('/hello/world'))->getDirectory());
        $this->assertEquals('/hello/world/', (new Path('/hello/world/'))->getDirectory());
        $this->assertEquals('/', (new Path('/'))->getDirectory());
        $this->assertEquals('', (new Path)->getDirectory());
        $this->assertEquals('/hello/world/.', (new Path('/hello/world/.'))->getDirectory());
        $this->assertEquals('/hello/world/..', (new Path('/hello/world/..'))->getDirectory());
    }

    /**
     * @covers pedroac\url\component\Path::getLastSegment
     */
    public function testGetLastSegment()
    {
        $this->assertEquals('world', (new Path('world'))->getLastSegment());
        $this->assertEquals('world', (new Path('/hello/world'))->getLastSegment());
        $this->assertEquals('world', (new Path('/hello/world/'))->getLastSegment());
        $this->assertEquals('world', (new Path('world'))->getLastSegment());
        $this->assertEquals('world', (new Path('world/'))->getLastSegment());
        $this->assertEquals('', (new Path(''))->getLastSegment());
        $this->assertEquals('', (new Path('/'))->getLastSegment());
        $this->assertEquals('', (new Path('//'))->getLastSegment());
        $this->assertEquals('', (new Path('///'))->getLastSegment());
        $this->assertEquals('.', (new Path('/hello/world/.'))->getLastSegment());
        $this->assertEquals('..', (new Path('/hello/world/..'))->getLastSegment());
    }


    /**
     * @covers pedroac\url\component\Path::getExtension
     */
    public function testGetExtension()
    {
        $this->assertEquals('ext', (new Path('file.ext'))->getExtension());
        $this->assertEquals('ext', (new Path('file.ext/'))->getExtension());
        $this->assertEquals('ext', (new Path('dir/folder/file.ext'))->getExtension());
        $this->assertEquals('ext', (new Path('dir.fk/folder/file.ext'))->getExtension());
        $this->assertEquals('ext', (new Path('.dir/folder/file.ext'))->getExtension());
        $this->assertEquals('ext', (new Path('file.prv.ext'))->getExtension());
        $this->assertEquals('ext', (new Path('.file.ext'))->getExtension(), '.file.ext');
        $this->assertEquals('', (new Path('.ext'))->getExtension());
        $this->assertEquals('', (new Path())->getExtension());
    }

    /**
     * @covers pedroac\url\component\Path::getAllExtensions
     */
    public function testGetAllExtensions()
    {
        $this->assertEquals(['ext'], (new Path('file.ext'))->getAllExtensions());
        $this->assertEquals(['prv', 'ext'], (new Path('file.prv.ext'))->getAllExtensions());
        $this->assertEquals([], (new Path('file'))->getAllExtensions());
        $this->assertEquals([], (new Path('.file'))->getAllExtensions());
        $this->assertEquals([], (new Path())->getAllExtensions());
    }
}
