<?php

namespace Dormilich\Utilities;

/**
 * An immutable version of an array object. Any method that can mutate an array 
 * will return a new array object instead.
 * 
 * Note: When you want to iterate over the same array multiple times you need to 
 * instantiate ArrayObject and let it create ArrayIterator instances that refer 
 * to it either by using foreach or by calling its getIterator() method manually. 
 * Another option is to create new Iterator instances via `ArrayList::from()`.
 * 
 * @uses SeekableIterator
 * @uses Countable
 * @uses Serializable
 */
class ArrayList extends \ArrayIterator implements \JsonSerializable
{
    /**
     * Instantiate array iterator. Transforms iterators to their array data.
     * 
     * @param mixed $input An array or object.
     * @param integer $flags Appropriate ArrayObject or ArrayIterator flags.
     * @return self
     */
    public function __construct( $input, $flags = 0 )
    {
        $input = self::convert( $input );

        parent::__construct( $input, $flags );
    }

    /**
     * Instantiate without explicitly using `new`. Transforms iterators to their 
     * array data.
     * 
     * @param mixed $input An array or object.
     * @param integer $flags Appropriate ArrayObject or ArrayIterator flags.
     * @return self
     */
    public static function from( $input, $flags = 0 )
    {
        $input = self::convert( $input );

        return new static( $input, $flags );
    }

    /**
     * Convert Iterator/IteratorAggregate objects to array.
     * 
     * @param mixed $input 
     * @return mixed
     */
    protected static function convert( $input )
    {
        if ( $input instanceof \IteratorAggregate ) {
            $input = $input->getIterator();
        }

        if ( $input instanceof \Iterator ) {
            $input = iterator_to_array( $input );
        }

        return $input;
    }

    /**
     * Create a new copy of itself from the given array data.
     * 
     * @param array $data 
     * @return self
     */
    protected function make( array $data )
    {
        return self::from( $data, $this->getFlags() );
    }

    /**
     * Test if a given method (in camelCase or snake_case) can be converted to 
     * an array function and execute it if such a function exists.
     * 
     * Note: Throws an exception if the used array function expects the array 
     *       to be given as a reference. (multisort, pop, push, shift, splice, 
     *       unshift, walk, walk_recursive)
     * 
     * @param string $name Method name.
     * @param array $args Method arguments.
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call( $name, array $args )
    {
        $fn = $this->funcFromMethod( $name );

        if ( is_callable( $fn ) ) {
            return $this->callFn( $fn, $args );
        }

        $msg = "Method '$name' does not exist for arrays";
        throw new \BadMethodCallException( $msg );
    }

    /**
     * Convert a method name into a syntactically equivalent array function name.
     * 
     * @param string $name Method name.
     * @return string Array function candidate.
     */
    private function funcFromMethod( $name )
    {
        return preg_replace_callback( '/[A-Z]/', function ( array $match ) {
            return '_' . strtolower( $match[ 0 ] );
        }, 'array' . ucfirst( $name ) );
    }

    /**
     * Call a function with all passed parameters on this object's data.
     * 
     * @param callable $fn A function name.
     * @param array $args The parameters for that function.
     * @return mixed Function result.
     */
    protected function callFn( callable $fn, array $args )
    {
        $this->validateFunction( $fn );

        array_unshift( $args, $this->getArrayCopy() );

        $result = call_user_func_array( $fn, $args );

        return $this->make( $result );
    }

    /**
     * Tests if the first parameter (i.e. this object's array) must be passed 
     * to the array function as a reference and rejects the function if it does 
     * since functions that modify the internal array are not desired and this 
     * implementation would sacrifice the functions' return value.
     * 
     * @param string $name An array function.
     * @return void
     * @throws BadMethodCallException
     */
    protected function validateFunction( callable $name )
    {
        $rf = new \ReflectionFunction( $name );
        $param = $rf->getParameters();

        if ( $param[ 0 ]->isPassedByReference() ) {
            $msg = "Function '$name()' cannot be used on this array object.";
            throw new \BadMethodCallException( $msg );
        }
    }

    /**
     * An implementation of array_map() that works as you would expect from the 
     * other array functions.
     * 
     * @param callable $fn A callback that receives the value as first and key 
     *                     as second parameter.
     * @return static
     */
    public function map( callable $fn )
    {
        $result = [];

        foreach ( $this as $key => $value ) {
            $result[ $key ] = call_user_func( $fn, $value, $key );
        }

        return $this->make( $result );
    }

    /**
     * A direct port of array_filter() for array objects. Using the key+value 
     * flag implicitly.
     * 
     * @param callable $fn A callback that receives the current value as first 
     *                     and the current key as second parameter.
     * @return static
     */
    public function filter( callable $fn )
    {
        $result = array_filter( $this->getArrayCopy(), $fn, \ARRAY_FILTER_USE_BOTH );

        return $this->make( $result );
    }

    /**
     * Filter off any array elements that match the condition. The inverse 
     * operation of array_filter().
     * 
     * @param callable $fn A callback that receives the current value as first 
     *                     and the current key as second parameter.
     * @return static
     */
    public function reject( callable $fn )
    {
        return $this->filter( function ( $value, $key ) use ( $fn ) {
            return ! $fn( $value, $key );
        } );
    }

    /**
     * A port of array_reduce() for array objects with the added possibility to 
     * use the array keys as well.
     * 
     * @param callable $fn A callback that receives the accumulator as first, 
     *                     the current value as second, and the current key as 
     *                     third parameter.
     * @param mixed $carry The initial value.
     * @return mixed
     */
    public function reduce( callable $fn, $carry )
    {
        foreach ( $this as $key => $value ) {
            $carry = call_user_func( $fn, $carry, $value, $key );
        }

        return $carry;
    }

    /**
     * Straightforward implementation of implode for array objects.
     * 
     * @param string $separator 
     * @return string
     */
    public function join( $separator = '' )
    {
        return implode( $separator, $this->getArrayCopy() );
    }

    /**
     * Immutable version of shuffle().
     * 
     * @return static
     */
    public function shuffle()
    {
        $array = $this->getArrayCopy();

        shuffle( $array );

        return $this->make( $array );
    }

    /**
     * Immutable version of array_unshift().
     * 
     * @return static
     */
    public function prepend( $value )
    {
        $array = $this->getArrayCopy();

        array_unshift( $array, $value );

        return $this->make( $array );
    }

    /**
     * Immutable version of append() for array objects.
     * 
     * @return static
     */
    public function append( $value )
    {
        $array = $this->getArrayCopy();

        array_push( $array, $value );

        return $this->make( $array );
    }

    /**
     * Immutable version of asort() for array objects.
     * 
     * @return static
     */
    public function asort( $flag = SORT_REGULAR )
    {
        $array = $this->getArrayCopy();

        asort( $array, $flag );

        return $this->make( $array );
    }

    /**
     * Immutable version of ksort() for array objects.
     * 
     * @return static
     */
    public function ksort( $flag = SORT_REGULAR )
    {
        $array = $this->getArrayCopy();

        ksort( $array, $flag );

        return $this->make( $array );
    }

    /**
     * Immutable version of uasort() for array objects.
     * 
     * @return static
     */
    public function uasort( $cmp_function )
    {
        $array = $this->getArrayCopy();

        uasort( $array, $cmp_function );

        return $this->make( $array );
    }

    /**
     * Immutable version of uksort() for array objects.
     * 
     * @return static
     */
    public function uksort( $cmp_function )
    {
        $array = $this->getArrayCopy();

        uksort( $array, $cmp_function );

        return $this->make( $array );
    }

    /**
     * Immutable version of natsort() for array objects.
     * 
     * @return static
     */
    public function natsort()
    {
        $array = $this->getArrayCopy();

        natsort( $array );

        return $this->make( $array );
    }

    /**
     * Immutable version of natcasesort() for array objects.
     * 
     * @return static
     */
    public function natcasesort()
    {
        $array = $this->getArrayCopy();

        natcasesort( $array );

        return $this->make( $array );
    }

    /**
     * @see https://php.net/jsonserializable
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }

    /**
     * Prevent mutation of the internal array.
     * 
     * @param string|integer $offset 
     * @param mixed $value 
     * @return void
     * @throws BadMethodCallException
     */
    public function offsetSet( $offset, $value )
    {
        throw new \BadMethodCallException( 'This array object is immutable!' );
    }

    /**
     * Prevent mutation of the internal array.
     * 
     * @param string|integer $offset 
     * @return void
     * @throws BadMethodCallException
     */
    public function offsetUnset( $offset )
    {
        throw new \BadMethodCallException( 'This array object is immutable!' );
    }
}
