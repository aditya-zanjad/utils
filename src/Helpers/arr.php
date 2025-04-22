<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Helpers;

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
function arr_first(array $arr)
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
function arr_first_key(array $arr)
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
function arr_last(array $arr)
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
function arr_last_key(array $arr): int|string
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
function arr_nth(array $arr, int $index)
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
function arr_nth_key(array $arr, int $index): null|int|string
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
 * Check if the given array path exists or not in the given array.
 *
 * @param   array   $arr
 * @param   string  $path
 *
 * @return  bool
 */
function arr_exists(array $arr, string $path): bool
{
    $keys       =   explode('.', $path);
    $ref        =   &$arr;
    $pathExists =   true;

    foreach ($keys as $key) {
        if (!array_key_exists($key, $ref) && !is_array($ref[$key])) {
            $pathExists = false;
            break;
        }

        $ref = &$ref[$key];
    }

    return $pathExists;
}

/**
 * Check that the value of the given array path equals to NULL.
 *
 * @param   array   $arr
 * @param   string  $path
 *
 * @return  bool
 */
function arr_null(array $arr, string $path): bool
{
    $keys   =   explode('.', $path);
    $ref    =   &$arr;
    $isNull =   false;

    foreach ($keys as $key) {
        if (!isset($key, $ref) && !is_array($ref[$key])) {
            $isNull = true;
            break;
        }

        $ref = &$ref[$key];
    }

    return $isNull;
}

/**
 * Check if the given array path exists & does not contain an "Empty Value".
 *
 * @param   array   $arr
 * @param   string  $path
 *
 * @return  bool
 */
function arr_empty(array $arr, string $path): bool
{
    $ref        =   &$arr;
    $pathKeys   =   explode('.', $path);
    $isEmpty    =   false;

    foreach ($pathKeys as $pathKey) {
        // The field must be set & not empty.
        if (!isset($ref[$pathKey]) || empty($ref[$pathKey])) {
            $isEmpty = true;
            break;
        }

        $ref = &$ref[$pathKey];
    }

    return $isEmpty;
}

/**
 * Get value of the specified array keys path from the given array.
 *
 * @param   array   $arr    =>  The array from which we want to retrieve the value for the given path.
 * @param   string  $path   =>  The array path whose value we want to fetch
 *
 * @return  mixed
 */
function arr_get(array $arr, string $path)
{
    $value = null;

    if (empty($arr)) {
        return $value;
    }

    $ref    =   &$arr;
    $keys   =   explode('.', $path);

    foreach ($keys as $key) {
        if (!array_key_exists($key, $ref) || !is_array($ref)) {
            break;
        }

        $ref = &$ref[$key];
    }

    return $ref;
}

/**
 * Try to get array value based on the given array path. If it does not exist, return the specified default value instead.
 *
 * @param   array<int|string, mixed>    $arr
 * @param   string                      $path
 * @param   mixed                       $default
 *
 * @return  mixed
 */
function arr_get_alt(array $arr, string $path, $default)
{
    $value = null;

    if (empty($arr)) {
        return $value;
    }

    $ref    =   &$arr;
    $keys   =   explode('.', $path);

    foreach ($keys as $key) {
        if (!array_key_exists($key, $ref) || !is_array($ref)) {
            break;
        }

        $ref = &$ref[$key];
    }

    if (is_null($ref)) {
        return $default;
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
function arr_set(array $arr, string $path, $value)
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
 * Remove more than one array elements by the given array keys.
 *
 * @param   array<int|string, mixed>    $arr
 * @param   string                      ...$paths
 *
 * @return  array<int|string, mixed>
 */
function arr_remove(array $arr, string ...$paths): array
{
    foreach ($paths as $path) {
        $ref        =   &$arr;
        $keys       =   explode('.', $path);
        $lastKey    =   array_pop($keys);

        foreach ($keys as $key) {
            if (!array_key_exists($key, $ref) || !is_array($ref[$key])) {
                break;
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
function arr_map_fn(array $arr, callable $fn): array
{
    if (empty($arr)) {
        return [];
    }

    $mapped = [];

    foreach ($arr as $key => $value) {
        $result = $fn($value, $key);

        if (!is_array($result)) {
            throw new Exception("[Developer][Exception]: The callback function must return an array.");
        }

        $firstKey           =   !function_exists('array_key_first') ? key($result) : array_key_first($result);
        $mapped[$firstKey]  =   $result[$firstKey];
    }

    return $mapped;
}

/**
 * Filter out the first value from the array based on the given callback condition.
 *
 * @param   array                                           $arr
 * @param   callable(mixed $value, int|string $key): bool   $fn
 *
 * @return  mixed
 */
function arr_first_fn(array|SplStack $arr, callable $fn)
{
    $filteredValue = null;

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
 * Filter out the last value from the array based on the given callback condition.
 *
 * @param   array<int|string, mixed>                        $arr
 * @param   callable(mixed $value, int|string $key): bool   $fn
 *
 * @return  array<int|string, mixed>
 */
function arr_last_fn(array $arr, callable $fn)
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
function arr_indexed(array $arr): bool
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
function arr_associative(array $arr): bool
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
function arr_dot(array $arr): array
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
function arr_undot(array $arr)
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
