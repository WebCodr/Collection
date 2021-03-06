<?php

namespace Collection\Test;

use Collection\MutableMap;

class MutableMapTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $list = new MutableMap('bubba', 'gump', 'shrimps');
        $array = array('bubba', 'gump', 'shrimps');

        $this->assertEquals($array, $list->getArray());
    }

    public function testConstructorWithArray()
    {
        $array = array('bubba', 'gump', 'shrimps');
        $list = new MutableMap($array);

        $this->assertEquals($array, $list->getArray());
    }

    public function testPropertyLength()
    {
        $list = new MutableMap('bubba', 'gump', 'shrimps');

        $this->assertEquals(3, $list->length);
    }

    public function testAssign()
    {
        $array = array('food' => 'shrimps', 'sauce' => 'cocktail');
        $list = new MutableMap();
        $list->assign($array);

        $this->assertEquals($array, $list->getArray());
    }

    public function testProperty()
    {
        $value = 'bar';
        $list = new MutableMap();
        $list->set('foo', $value);

        $this->assertEquals($value, $list->get('foo'));
    }

    public function testPropertyWithArray()
    {
        $value = array('foo' => 'bar');
        $list = new MutableMap();
        $list->set('foo', array('foo' => 'bar'));

        $this->assertEquals($value, $list->get('foo', false));
    }

    public function testPropertyWithMap()
    {
        $value = array('foo' => 'bar');
        $list = new MutableMap();
        $list->set('foo', array('foo' => 'bar'));

        $this->assertEquals(new MutableMap($value), $list->get('foo'));
    }

    public function testMagicProperty()
    {
        $value = 'bar';
        $list = new MutableMap();
        $list->foo = $value;

        $this->assertTrue(isset($list->foo));
        $this->assertEquals($value, $list->foo);
    }

    /**
     * @expectedException \OutOfBoundsException
     */

    public function testNonExistingProperty()
    {
        $list = new MutableMap();
        $value = $list->get('foo');
    }

    /**
     * @expectedException \OutOfBoundsException
     */

    public function testRemoveProperty()
    {
        $property = 'foo';
        $value = 'bar';
        $list = new MutableMap();
        $list->set($property, $value);
        $list->remove($property);
        $value = $list->get($property);
    }

    /**
     * @expectedException \OutOfBoundsException
     */

    public function testMagicRemoveProperty()
    {
        $property = 'foo';
        $value = 'bar';
        $list = new MutableMap();
        $list->{$property} = $value;
        unset($list->{$property});
        $value = $list->{$property};
    }

    /**
     * @expectedException \OutOfBoundsException
     */

    public function testRemoveNonExistingProperty()
    {
        $list = new MutableMap();
        $list->remove('foo');
    }

    public function testCount()
    {
        $list = new MutableMap();
        $list->set('foo', 'bar');

        $this->assertEquals(1, count($list));
    }

    public function testIteration()
    {
        $array = array('food' => 'shrimps', 'sauce' => 'cocktail');
        $list = new MutableMap();
        $list->assign($array);

        $newArray = array();

        foreach ($list as $key => $value) {
            $newArray[$key] = $value;
        }

        $this->assertEquals($array, $newArray);
    }

    public function testUpdateProperties()
    {
        $values = array('foo' => 'bar');
        $list = new MutableMap();
        $list->assign($values);
        $newValues = array('bar' => 'foo');
        $list->update($newValues);
        $expected = array_merge($values, $newValues);
        $this->assertEquals($expected, $list->getArray());
    }

    public function testToString()
    {
        $list = new MutableMap('bubba', 'gump', 'shrimps');
        $array = array('bubba', 'gump', 'shrimps');

        $this->assertEquals(serialize($array), (string)$list);
    }

    public function testHead()
    {
        $array = array('foo', 'bar');
        $list = new MutableMap('foo', 'bar');

        $this->assertEquals(reset($array), $list->first());
    }

    public function testLast()
    {
        $array = array('foo', 'bar');
        $list = new MutableMap('foo', 'bar');

        $this->assertEquals(end($array), $list->last());
    }

    public function testReverse()
    {
        $expected = new MutableMap('bar', 'foo');
        $list = new MutableMap('foo', 'bar');

        $this->assertEquals($expected, $list->reverse());
    }

    public function testEach()
    {
        $expected = new MutableMap(array('foo' => 'bar'));
        $result = new MutableMap();

        $expected->each(function ($value, $key) use($result) {
            $result->set($key, $value);
        });

        $this->assertEquals($expected, $result);
    }

    public function testMap()
    {
        $expected = new MutableMap('FOO', 'BAR');
        $list = new MutableMap('foo', 'bar');

        $list->map(function ($value) {
            return strtoupper($value);
        });

        $this->assertEquals($expected, $list);
    }

    public function testFilter()
    {
        $expected = new MutableMap('foo');
        $list = new MutableMap('foo', 'bar');

        $filter = function ($value) {
            if ($value === 'foo') {
                return true;
            }
        };

        $this->assertEquals($expected, $list->filter($filter));
    }

    public function testSlice()
    {
        $expected = new MutableMap('bar');
        $list = new MutableMap('foo', 'bar');

        $this->assertEquals($expected, $list->slice(1, 1));
    }

    public function testChaining()
    {
        $expected = new MutableMap('FOO');
        $list = new MutableMap('foo', 'bar');
        $filter = function ($value) {
            if (strtolower($value) == 'foo') {
                return true;
            }
        };

        $list->map(function ($value) {
            return strtoupper($value);
        });

        $this->assertEquals($expected->all(), $list->filter($filter)->all());
    }

    public function testHas()
    {
        $map = new MutableMap();
        $map->set('foo', 'bar');

        $this->assertTrue($map->has('foo'));
        $this->assertFalse($map->has('bar'));
    }

    public function testJoin()
    {
        $map = new MutableMap('foo', 'bar');
        $this->assertEquals('foo bar', $map->join(' '));
    }

    public function testUnique()
    {
        $map = new MutableMap('foo', 'foo');
        $map->unique();

        $this->assertEquals(1, $map->count());
    }

    public function testSort()
    {
        $map = new MutableMap('c', 'b', 'a');
        $map->sort();

        $this->assertEquals(['a', 'b', 'c'], $map->getArray());
    }

    public function testSortCustom()
    {
        $map = new MutableMap('a', 'b', 'c');
        $map->sort(function($a, $b) {
            return !strcmp($a, $b);
        });

        $this->assertEquals(['c', 'b', 'a'], $map->getArray());
    }

    public function testShift()
    {
        $map = new MutableMap('a', 'b', 'c');
        $value = $map->shift();

        $this->assertEquals('a', $value);
        $this->assertEquals(['b', 'c'], $map->getArray());
    }

    public function testPop()
    {
        $map = new MutableMap('a', 'b', 'c');
        $value = $map->pop();

        $this->assertEquals('c', $value);
        $this->assertEquals(['a', 'b'], $map->getArray());
    }

    public function testUnshift()
    {
        $map = new MutableMap('b', 'c');
        $map->unshift('a');

        $this->assertEquals(['a', 'b', 'c'], $map->getArray());
    }

    public function testPush()
    {
        $map = new MutableMap('a', 'b');
        $map->push('c');

        $this->assertEquals(['a', 'b', 'c'], $map->getArray());
    }

    public function testIndex()
    {
        $map = new MutableMap('a', 'b');

        $this->assertEquals(1, $map->index('b'));
    }

    /**
     * @expectedException \OutOfBoundsException
     */

    public function testDelete()
    {
        $map = new MutableMap('a', 'b');
        $map->delete('b');
        $map->get(1);
    }
}