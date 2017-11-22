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
}
