<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

class AggregateFunctionsTest extends TestCase
{
    public function testJoin()
    {
        $str = ArrayList::from( [ 'a', 'b', 'c' ] )->join( ',' );

        $this->assertSame( 'a,b,c', $str );
    }

    public function testDefaultJoin()
    {
        $str = ArrayList::from( [ 'a', 'b', 'c' ] )->join();

        $this->assertSame( 'abc', $str );
    }

    public function testReduce()
    {
        $str = ArrayList::from( [ 'a', 'b', 'c' ] )
            ->reduce( function ( $aggr, $value, $key ) {
                return $aggr .= $value . $key . '-';
            }, '-' );

        $this->assertSame( '-a0-b1-c2-', $str );
    }

    public function testContains()
    {
        $list = ArrayList::from( [ '1', '2', '3' ] );

        $this->assertTrue( $list->contains( '2' ) );
        $this->assertFalse( $list->contains( 2 ) );
    }
}
