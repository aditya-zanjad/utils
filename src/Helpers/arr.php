<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Helpers;

use Iterator;
use SplStack;
use Exception;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Get the first element of the array.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return mixed
 */
function arrFirst(array $arr)
{
    if (empty($arr)) {
        return null;
    }

    if (!function_exists('array_key_first')) {
        reset($arr);
        return current($arr);
    }

    return $arr[array_key_first($arr)];
}

/**
 * Get the first key of the given array.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return int|string
 */
function arrFirstKey(array $arr)
{
    if (!function_exists('array_key_first')) {
        reset($arr);
        return current($arr);
    }

    return array_key_first($arr);
}

/**
 * Get the last element of the array.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return mixed
 */
function arrLast(array $arr)
{
    if (empty($arr)) {
        return null;
    }

    if (!function_exists('array_key_last')) {
        return end($arr);
    }

    return $arr[array_key_last($arr)];
}

/**
 * Get the last key of the given array.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return int|string
 */
function arrLastKey(array $arr): int|string
{
    if (function_exists('array_key_last')) {
        return array_key_last($arr);
    }

    return key(end($arr));
}

/**
 * Get the element located at the nth position in the given array.
 *
 * @param   array<int|string, mixed>    $arr
 * @param   int                         $index
 *
 * @return  null|int|string
 */
function arrNth(array $arr, int $index)
{
    if (empty($arr) || count($arr) < abs($index)) {
        return null;
    }

    $sliced = array_slice($arr, $index, 1);
    return $sliced[0];
}

/**
 * Get the array key located at the given index in the array.
 *
 * @param   array<int|string, mixed>    $arr
 * @param   int                         $index
 *
 * @return  null|int|string
 */
function arrNthKey(array $arr, int $index): null|int|string
{
    if (empty($arr)) {
        return null;
    }

    if (count($arr) < $index) {
        return null;
    }

    $sliced = array_slice($arr, $index, 1, true);

    if (!function_exists('array_key_first')) {
        reset($sliced);
        return key($sliced);
    }

    return array_key_first($sliced);
}

/**
 * Get a random value from the array.
 * 
 * @param array $arr
 * 
 * @return mixed
 */
function arrRandom(array $arr)
{
    if (empty($arr)) {
        return null;
    }

    $randomKey = array_rand($arr);
    return $arr[$randomKey];
}

/**
 * Get a random key from the array.
 * 
 * @param array $arr
 * 
 * @return mixed
 */
function arrRandomKey(array $arr)
{
    if (empty($arr)) {
        return null;
    }

    $randomKey = array_rand($arr);
    return $arr[$randomKey];
}

/**
 * Get a random value from the array.
 * 
 * @param   array<int|string, mixed>    $arr
 * @param   int                         $quantity
 * 
 * @return  array<int|string, mixed>
 */
function arrRandoms(array $arr, int $quantity = 2)
{
    if (empty($arr)) {
        return null;
    }

    if ($quantity < 2) {
        throw new Exception("[Developer][Exception]: The value of the parameter quantity should be less than 2.");
    }

    if (count($arr) < $quantity) {
        throw new Exception("[Developer][Exception]: The value of the parameter quantity should not be greater than the size of the array.");
    }

    $randomKeys =   array_rand($arr, $quantity);
    $result     =   [];

    foreach ($randomKeys as $randomKey) {
        $result[$randomKey] = $arr[$randomKey];
    }

    return $result;
}

/**
 * Get a random key from the array.
 * 
 * @param   array<int|string, mixed>    $arr
 * @param   int                         $quantity
 * 
 * @return  array<int|string, mixed>
 */
function arrRandomKeys(array $arr, int $quantity = 2)
{
    if (empty($arr)) {
        return null;
    }

    if ($quantity < 2) {
        throw new Exception("[Developer][Exception]: The value of the parameter quantity should be less than 2.");
    }

    if (count($arr) < $quantity) {
        throw new Exception("[Developer][Exception]: The value of the parameter quantity should not be greater than the size of the array.");
    }

    return array_rand($arr, $quantity);
}

/**
 * Check if the given dot notation path exists or not in the given array.
 * 
 * ======================================================================================
 * This function uses PHP's built-in 'array_key_exists()' function. It checks if each of
 * the given array path keys exist or not in the given array. If any of these keys is
 * missing, this function will return a boolean false. Otherwise, a boolean true is
 * returned.
 * ======================================================================================
 *
 * @param   array   $arr
 * @param   string  $path
 *
 * @return  bool
 */
function arrPathExists(array $arr, string $path): bool
{
    $keys   =   explode('.', $path);
    $ref    =   &$arr;

    foreach ($keys as $key) {
        $ref = &$ref[$key];

        if (!array_key_exists($key, $ref)) {
            return false;
        }
    }

    return true;
}

/**
 * Check if value of the given dot notation array path equals to NULL or not.
 * 
 * ======================================================================================
 * This function uses PHP's built-in 'isset()' function. Therefore, it will check for 
 * following two conditions:
 *      [1] If the each of the array keys of the nested path exists or not.
 *      [2] If the value of the given array path is equal to NULL or not.
 * 
 * If any of the above conditions fail, a boolean false will be returned. Otherwise, a
 * boolean true is returned.
 * ======================================================================================
 *
 * @param   array   $arr
 * @param   string  $path
 *
 * @return  bool
 */
function arrPathNull(array $arr, string $path): bool
{
    $keys   =   explode('.', $path);
    $ref    =   &$arr;

    foreach ($keys as $key) {
        $ref = &$ref[$key];

        if (!isset($ref[$key])) {
            return true;
        }
    }

    return false;
}

/**
 * Check if value of the given array path exists & if it's an 'empty value' or not.
 * 
 * ======================================================================================
 * This function uses PHP's built-in 'isset()' & 'empty()' functions. Therefore, it will 
 * check for following two conditions:
 *      [1] If the each of the array keys of the nested path exists or not.
 *      [2] If the value of the given array path is an empty value or not.
 * 
 * If any of the above conditions fail, a boolean false will be returned. Otherwise, a
 * boolean true is returned.
 * ======================================================================================
 *
 * @param   array   $arr
 * @param   string  $path
 *
 * @return  bool
 */
function arrPathEmpty(array $arr, string $path): bool
{
    $ref        =   &$arr;
    $pathKeys   =   explode('.', $path);

    foreach ($pathKeys as $pathKey) {
        // The field must be set & not empty.
        if (!isset($ref[$pathKey]) || empty($ref[$pathKey])) {
            return true;
        }

        $ref = &$ref[$pathKey];
    }

    return false;
}

/**
 * Get value of the specified array keys path from the given array.
 *
 * @param   array   $arr    =>  The array from which we want to retrieve the value for the given path.
 * @param   string  $path   =>  The array path whose value we want to fetch
 *
 * @return  mixed
 */
function arrGet(array $arr, string $path)
{
    if (empty($arr)) {
        return null;
    }

    $ref    =   &$arr;
    $keys   =   explode('.', $path);

    foreach ($keys as $key) {
        if (!isset($ref[$key])) {
            return null;
        }

        $ref = &$ref[$key];
    }

    return $ref;
}

/**
 * Set value of the specified array keys path from the given array.
 *
 * @param   array<int|string, mixed>    &$arr
 * @param   string                      $path
 * @param   mixed                       $value
 *
 * @return  array<int|string, mixed>
 */
function arrSet(array $arr, string $path, $value)
{
    $ref    =   &$arr;
    $keys   =   explode('.', $path);

    foreach ($keys as $key) {
        /**
         * If the key specified in the given path does not exist or is not an array,
         * set it to an empty array. This way, we'll keep going inside the
         * nested structure of the array, until the last key.
         */
        if (!array_key_exists($key, $ref) || !is_array($ref[$key])) {
            $ref[$key] = [];
        }

        $ref = &$ref[$key];
    }

    $ref = $value;  // Assign given value to the final nested key of the array.
    return $arr;
}

/**
 * Remove an element from the array based on the given path.
 *
 * @param   array<int|string, mixed>    $arr
 * @param   string                      $paths
 *
 * @return  array<int|string, mixed>
 */
function arrRemove(array $arr, string $path): array
{
    $ref        =   &$arr;
    $keys       =   explode('.', $path);
    $lastKey    =   array_pop($keys);

    foreach ($keys as $key) {
        if (!array_key_exists($key, $ref) || !is_array($ref[$key])) {
            continue;
        }

        $ref = &$ref[$key];
    }

    unset($ref[$lastKey]);
    return $arr;

    foreach ($paths as $path) {
        $ref        =   &$arr;
        $keys       =   explode('.', $path);
        $lastKey    =   array_pop($keys);

        foreach ($keys as $key) {
            if (!array_key_exists($key, $ref) || !is_array($ref[$key])) {
                continue;
            }

            $ref = &$ref[$key];
        }

        unset($ref[$lastKey]);
    }

    return $arr;
}

/**
 * Remove more than one elements from the array based on the given path.
 * 
 * @param   array<int|string, mixed>    $arr
 * @param   string                      $paths
 *
 * @return  array<int|string, mixed>
 */
function arrRemoveMany(array $arr, string ...$paths)
{
    foreach ($paths as $path) {
        $ref        =   &$arr;
        $keys       =   explode('.', $path);
        $lastKey    =   array_pop($keys);

        foreach ($keys as $key) {
            if (!array_key_exists($key, $ref) || !is_array($ref[$key])) {
                continue 2;
            }

            $ref = &$ref[$key];
        }

        unset($ref[$lastKey]);
    }

    return $arr;
}

/**
 * Apply the given callback function to both the array keys & array values.
 * 
 * @param   array                                           $arr
 * @param   callable(mixed $value, int|string $key): mixed  $fn
 *
 * @return  array<int|string, mixed>
 */
function arrMapFn(array $arr, callable $fn): array
{
    if (empty($arr)) {
        return [];
    }

    $mapper = function (array $arr, callable $fn): Iterator {
        foreach ($arr as $key => $value) {
            $result = $fn($value, $key);

            if (!is_array($result) || empty($result) || count($result) > 1) {
                throw new Exception("[Developer][Exception]: The callback function must return an array containing a [key => value] pair.");
            }

            $firstKey = !function_exists('array_key_first') ? key($result) : array_key_first($result);
            yield $firstKey => $result[$firstKey];
        }
    };

    return iterator_to_array($mapper($arr, $fn));
}

/**
 * Filter out the first value from the array based on the given callback condition.
 *
 * @param   array                                           $arr
 * @param   callable(mixed $value, int|string $key): bool   $fn
 *
 * @return  mixed
 */
function arrFirstFn(array|SplStack $arr, callable $fn)
{
    $filter = function (array $arr, callable $fn): Iterator {
        foreach ($arr as $key => $value) {
            $result = call_user_func($fn, $value, $key);

            if (!is_bool($result)) {
                throw new Exception("[Developer][Exception]: The given callback function must always return a boolean value.");
            }

            if ($result === true) {
                yield $value;
                break;
            }
        }
    };

    return iterator_to_array($filter($arr, $fn));
}

/**
 * Filter out the last value from the array based on the given callback condition.
 *
 * @param   array<int|string, mixed>                        $arr
 * @param   callable(mixed $value, int|string $key): bool   $fn
 *
 * @return  array<int|string, mixed>
 */
function arrLastFn(array $arr, callable $fn)
{
    $arr            =   new SplStack($arr);
    $filteredValue  =   null;

    foreach ($arr as $key => $value) {
        $result = call_user_func($fn, $value, $key);

        if (!is_bool($result)) {
            throw new Exception("[Developer][Exception]: The given callback function must always return a boolean value.");
        }

        if ($result === true) {
            $filteredValue = $value;
            break;
        }
    }

    return $filteredValue;
}

/**
 * Check if the given array is an indexed array or not.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return bool
 */
function arrIsIndexed(array $arr): bool
{
    if (empty($arr)) {
        return true;
    }

    if (function_exists('array_is_list')) {
        return array_is_list($arr);
    }

    return array_keys($arr) === range(0, count($arr) - 1);
}

/**
 * Check if the given array is an associative array or not.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return bool
 */
function arrIsAssociative(array $arr): bool
{
    if (empty($arr)) {
        return false;
    }

    if (function_exists('array_is_list')) {
        return !array_is_list($arr);
    }

    return array_keys($arr) !== range(0, count($arr) - 1);
}

/**
 * Convert the given nested array path to dot path notation.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return array<int|string, mixed>
 */
function arrDot(array $arr): array
{
    if (empty($arr)) {
        return $arr;
    }

    $arrIterator    =   new RecursiveArrayIterator($arr);
    $iterator       =   new RecursiveIteratorIterator($arrIterator, RecursiveIteratorIterator::SELF_FIRST);
    $result         =   [];
    $nestedPathKeys =   [];

    foreach ($iterator as $key => $value) {
        $nestedPathKeys[$iterator->getDepth()] = $key;

        if (is_array($value) && !empty($value)) {
            continue;
        }

        $nestedPathKeys     =   array_slice($nestedPathKeys, 0, $iterator->getDepth() + 1);
        $dotPath            =   implode('.', $nestedPathKeys);
        $result[$dotPath]   =   $value;
    }

    return $result;
}

/**
 * Convert the given dot path array to the nested one.
 *
 * @param array<string, mixed> $arr
 *
 * @return array<int|string, mixed>
 */
function arrUndot(array $arr)
{
    $result = [];

    foreach ($arr as $path => $value) {
        $ref    =   &$arr;
        $keys   =   explode('.', $path);

        foreach ($keys as $key) {
            /**
             * If the key specified in the given path does not exist or is not an array,
             * set it to an empty array. This way, we'll keep going inside the
             * nested structure of the array, until the last key.
             */
            if (!array_key_exists($key, $ref) || !is_array($ref[$key])) {
                $ref[$key] = [];
            }

            $ref = &$ref[$key];
        }

        $ref = $value;  // Assign given value to the final nested key of the array.
    }

    return $result;
}

/**
 * Convert the nested array structure into an indexed array.
 * 
 * @param array $arr
 * 
 * @return array
 */
function arrFlatten(array $arr): array
{
    if (empty($arr)) {
        return [];
    }

    if (class_exists(RecursiveIteratorIterator::class) && class_exists(RecursiveArrayIterator::class)) {
        $arrIterator    =   new RecursiveArrayIterator($arr);
        $iterator       =   new RecursiveIteratorIterator($arrIterator, RecursiveIteratorIterator::SELF_FIRST);
        $result         =   [];
        $nestedPathKeys =   [];

        foreach ($iterator as $key => $value) {
            $nestedPathKeys[$iterator->getDepth()] = $key;

            if (is_array($value)) {
                continue;
            }

            $result[] = $value;
        }

        return $result;
    }

    $flattener = function (array $arr) use (&$flattener) {
        $result = [];

        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $flattener($arr));
                continue;
            }

            $result[] = $value;
        }

        return $result;
    };

    return $flattener($arr);
}
