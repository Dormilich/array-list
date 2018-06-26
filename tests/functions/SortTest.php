<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    public function testShuffle()
    {
        $a = ArrayList::from( range( 1, 10 ) );
        $b = $a->shuffle();

        $this->assertNotSame( $a, $b );
        $this->assertSame( count( $a ), count( $b ) );
        $this->assertNotEquals( $a->getArrayCopy(), $b->getArrayCopy() );
    }

    public function testAsort()
    {
        $array = [ 'a' => 3, 'b' => 1, 'c' => 2 ];

        $a = ArrayList::from( $array );
        $b = $a->asort( SORT_NUMERIC );

        $exp = [ 'b' => 1, 'c' => 2, 'a' => 3 ];

        $this->assertEquals( $array, $a->getArrayCopy() );
        $this->assertEquals( $exp, $b->getArrayCopy() );
    }

    public function testKsort()
    {
        $array = [ 'b' => 1, 'c' => 2, 'a' => 3 ];

        $a = ArrayList::from( $array );
        $b = $a->ksort();

        $exp = [ 'a' => 3, 'b' => 1, 'c' => 2 ];

        $this->assertEquals( $array, $a->getArrayCopy() );
        $this->assertEquals( $exp, $b->getArrayCopy() );
    }

    public function testUasort()
    {
        $array = [ 'a' => 'y', 'b' => 'x', 'c' => 'z' ];

        $a = ArrayList::from( $array );
        $b = $a->uasort( 'strcmp' );

        $exp = [ 'b' => 'x', 'a' => 'y', 'c' => 'z' ];

        $this->assertEquals( $array, $a->getArrayCopy() );
        $this->assertEquals( $exp, $b->getArrayCopy() );
    }

    public function testUksort()
    {
        $array = [ 'b' => 'x', 'a' => 'y', 'c' => 'z' ];

        $a = ArrayList::from( $array );
        $b = $a->uksort( 'strcmp' );

        $exp = [ 'a' => 'y', 'b' => 'x', 'c' => 'z' ];

        $this->assertEquals( $array, $a->getArrayCopy() );
        $this->assertEquals( $exp, $b->getArrayCopy() );
    }

    public function testNatsort()
    {
        $array = [ 'a2', 'a11', 'A5', 'a1', 'a10' ];

        $a = ArrayList::from( $array );
        $b = $a->natsort();

        $exp = [ 'A5', 'a1', 'a2', 'a10', 'a11' ];

        $this->assertEquals( $array, $a->getArrayCopy() );
        $this->assertEquals( $exp, array_values( $b->getArrayCopy() ) );
    }

    public function testNatcasesort()
    {
        $array = [ 'A2', 'a11', 'a1', 'A10' ];

        $a = ArrayList::from( $array );
        $b = $a->natcasesort();

        $exp = [ 'a1', 'A2', 'A10', 'a11' ];

        $this->assertEquals( $array, $a->getArrayCopy() );
        $this->assertEquals( $exp, array_values( $b->getArrayCopy() ) );
    }
}
