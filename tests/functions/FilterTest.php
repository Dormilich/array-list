<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testFilterValue()
    {
        $array = [ 'foo' => -1, 'bar' => 2 ];

        $list = ArrayList::from( $array )
            ->filter( function ( $value ) {
                return $value > 0;
            } )
        ;

        $exp = [ 'bar' => 2 ];
        $this->assertEquals( $exp, $list->getArrayCopy() );
    }

    public function testFilterKey()
    {
        $array = [ 'foo' => -1, 'bar' => 2 ];

        $list = ArrayList::from( $array )
            ->filter( function ( $value, $key ) {
                return $key === 'foo';
            } )
        ;

        $exp = [ 'foo' => -1 ];
        $this->assertEquals( $exp, $list->getArrayCopy() );
    }

    public function testRejectValue()
    {
        $array = [ 'foo' => -1, 'bar' => 2 ];

        $list = ArrayList::from( $array )
            ->reject( function ( $value ) {
                return $value > 0;
            } )
        ;

        $exp = [ 'foo' => -1 ];
        $this->assertEquals( $exp, $list->getArrayCopy() );
    }

    public function testRejectKey()
    {
        $array = [ 'foo' => -1, 'bar' => 2 ];

        $list = ArrayList::from( $array )
            ->reject( function ( $value, $key ) {
                return $key === 'foo';
            } )
        ;

        $exp = [ 'bar' => 2 ];
        $this->assertEquals( $exp, $list->getArrayCopy() );
    }
}
