<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

class ImmutableTest extends TestCase
{
    /**
     * @expectedException BadMethodCallException
     */
    public function testUnsetItemFails()
    {
        $list = new ArrayList( [ 'foo' => 1, 'bar' => 2 ] );

        unset( $list[ 'foo' ] );
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetItemFails()
    {
        $list = new ArrayList( [ 'foo' => 1, 'bar' => 2 ] );

        $list[ 'bar' ] = 'something';
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testAddItemDirectlyFails()
    {
        $list = new ArrayList( [ 'foo' => 1, 'bar' => 2 ] );

        $list[ 'xyz' ] = 'something';
    }

    public function testGetItem()
    {
        $list = new ArrayList( [ 'foo' => 1, 'bar' => 2 ] );

        $this->assertSame( 1, $list[ 'foo' ] );
    }

    public function testCheckItemExists()
    {
        $list = new ArrayList( [ 'foo' => 1, 'bar' => 2 ] );

        $this->assertTrue( isset( $list[ 'foo' ] ) );
        $this->assertFalse( isset( $list[ 'xyz' ] ) );
    }
}
