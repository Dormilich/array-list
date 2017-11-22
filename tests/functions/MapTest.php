<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    public function testMapValues()
    {
        $array = [ 'foo' => 1, 'bar' => 2 ];

        $data = ArrayList::from( $array )
            ->map( function ( $value ) {
                return $value * 2;
            } )
            ->getArrayCopy()
        ;

        $exp = [ 'foo' => 2, 'bar' => 4 ];
        $this->assertEquals( $exp, $data );
    }

    public function testMapKeys()
    {
        $array = [ 'foo', 'bar' ];

        $data = ArrayList::from( $array )
            ->map( function ( $value, $key ) {
                return $key + 3;
            } )
            ->getArrayCopy()
        ;

        $exp = [ 3, 4 ];
        $this->assertEquals( $exp, $data );
    }
}
