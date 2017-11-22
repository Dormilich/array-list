<?php

use Dormilich\Utilities\ArrayList;
use PHPUnit\Framework\TestCase;

class ModifyTest extends TestCase
{
    public function testAppendValue()
    {
        $a = new ArrayList( [ 'a' ] );
        $b = $a->append( 1 );

        $this->assertCount( 1, $a );
        $this->assertCount( 2, $b );
        $this->assertEquals( [ 'a', 1 ], $b->getArrayCopy() );
    }

    public function testPrependValue()
    {
        $a = new ArrayList( [ 'a' ] );
        $b = $a->prepend( 1 );

        $this->assertCount( 1, $a );
        $this->assertCount( 2, $b );
        $this->assertEquals( [ 1, 'a' ], $b->getArrayCopy() );
    }
}
