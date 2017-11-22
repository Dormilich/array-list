<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testCreateFromArray()
    {
        $array = [ 'foo', 'bar' ];

        $list = new ArrayList( $array );

        $this->assertEquals( $array, $list->getArrayCopy() );
    }

    public function testCreateFromObject()
    {
        $object = new stdClass;
        $object->foo = 1;
        $object->bar = 'something';

        $list = new ArrayList( $object );
        $data = $list->getArrayCopy();

        $this->assertCount( 2, $data );
        $this->assertArrayHasKey( 'foo', $data );
        $this->assertArrayHasKey( 'bar', $data );
        $this->assertSame( 1, $data[ 'foo' ] );
        $this->assertSame( 'something', $data[ 'bar' ] );
    }

    public function testCreateFromIterator()
    {
        $glob = new GlobIterator( __DIR__ );
        $list = new ArrayList( $glob );

        $this->assertSame( count( $glob ), count( $list ) );
    }
/*
    // proof that the native ArrayIterator doesn't handle iterators
    public function testIteratorFromIteratorFails()
    {
        $glob = new GlobIterator( __DIR__ );
        $list = new ArrayIterator( $glob );

        $this->assertCount( 0, $list );
        $this->assertNotSame( count( $glob ), count( $list ) );
    }
*/
    /**
     * @depends testCreateFromIterator
     */
    public function testCreateFromIteratorAggregate()
    {
        $glob = new GlobIterator( __DIR__ );

        $obj = $this->createMock( 'IteratorAggregate' );
        $obj->method( 'getIterator' )
            ->will( $this->returnValue( $glob ) );

        $list = new ArrayList( $obj );

        $this->assertSame( count( $glob ), count( $list ) );
    }
}
