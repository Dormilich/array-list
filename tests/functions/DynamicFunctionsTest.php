<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

/**
 * Tests for functions that make sense in this context or are explicitly rejected.
 */
class DynamicFunctionsTest extends TestCase
{
    public function testKeys()
    {
        $array = [ 'a' => 'y', 'b' => 'x', 'c' => 'z' ];

        $keys = ArrayList::from( $array )->keys();

        $exp = [ 'a', 'b', 'c' ];

        $this->assertEquals( $exp, $keys->getArrayCopy() );
    }

    public function testValues()
    {
        $array = [ 'b' => 'x', 'a' => 'y', 'c' => 'z' ];

        $values = ArrayList::from( $array )->values();

        $exp = [ 'x', 'y', 'z' ];

        $this->assertEquals( $exp, $values->getArrayCopy() );
    }

    public function testCountValues()
    {
        $array = [ 1, 'hello', 1, 'world', 'hello' ];

        $count = ArrayList::from( $array )->countValues();

        $exp = [ 1 => 2, 'hello' => 2, 'world' => 1 ];

        $this->assertEquals( $exp, $count->getArrayCopy() );
    }

    public function testReverseIndexed()
    {
        $list = ArrayList::from( [ 1, 2, 3 ] )->reverse();

        $exp = [ 3, 2, 1 ];

        $this->assertEquals( $exp, $list->getArrayCopy() );
    }

    public function testReverseAssociative()
    {
        $array = [ 'a' => 'x', 'b' => 'y', 'c' => 'z' ];

        $list = ArrayList::from( $array )->reverse();

        $exp = [ 'c' => 'z', 'b' => 'y', 'a' => 'x' ];

        $this->assertEquals( $exp, $list->getArrayCopy() );
    }

    public function testUnique()
    {
        $array = [ 9, 3, 0, 4, 9, 4, 3, 0, 4, 1, 0 ];

        $unique = ArrayList::from( $array )->unique();

        $exp = [ 9, 3, 0, 4, 9 => 1 ];

        $this->assertEquals( $exp, $unique->getArrayCopy() );
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Method 'test' does not exist for arrays
     */
    public function testUnknownFunctionFails()
    {
        ArrayList::from( [] )->test();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Function 'array_multisort()' cannot be used on this array object
     */
    public function testMultisort()
    {
        ArrayList::from( [] )->multisort();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Function 'array_pop()' cannot be used on this array object
     */
    public function testPopFails()
    {
        ArrayList::from( [] )->pop();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Function 'array_push()' cannot be used on this array object
     */
    public function testPushFails()
    {
        ArrayList::from( [] )->push();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Function 'array_shift()' cannot be used on this array object
     */
    public function testShiftFails()
    {
        ArrayList::from( [] )->shift();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Function 'array_splice()' cannot be used on this array object
     */
    public function testSpliceFails()
    {
        ArrayList::from( [] )->splice();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Function 'array_unshift()' cannot be used on this array object
     */
    public function testUnshiftFails()
    {
        ArrayList::from( [] )->unshift();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Function 'array_walk()' cannot be used on this array object
     */
    public function testWalkFails()
    {
        ArrayList::from( [] )->walk();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Function 'array_walk_recursive()' cannot be used on this array object
     */
    public function testWalkRecursiveFails()
    {
        ArrayList::from( [] )->walkRecursive();
    }
}
