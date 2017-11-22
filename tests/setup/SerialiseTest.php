<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

class SerialiseTest extends TestCase
{
    public function testCastToArray()
    {
        $glob = new GlobIterator( __DIR__ );
        $list = new ArrayList( $glob );

        $this->assertEquals( $list->getArrayCopy(), (array) $list );
    }

    public function testSaveAsJson()
    {
        $array = [ 'foo' => 1, 'bar' => 2 ];
        $list = new ArrayList( $array );

        $this->assertSame( json_encode( $array ), json_encode( $list ) );
    }
}
