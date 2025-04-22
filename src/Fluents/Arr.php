<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Fluents;

use SplStack;
use Exception;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * @version 1.0
 */
class Arr
{
    /**
     * @var array $arr
     */
    protected array $arr;

    /**
     * @var bool $isImmutable
     */
    protected bool $isImmutable = false;

    /**
     * @param array $arr
     */
    public function __construct(array $arr, array $options = [])
    {
        $this->arr          =   $arr;
        $this->isImmutable  =   (bool) ($options['immutable'] ?? false);
    }

    /**
     * Get the uderlying array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->arr;
    }

    /**
     * Return an appropriate class instance depending on whether the immutable mode is turned on or off.
     *
     * @return static
     */
    protected function this(array $result): static
    {
        if ($this->isImmutable) {
            return new static($result);
        }

        $this->arr = $result;
        return $this;
    }

    /**
     * Check if the current array is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->arr);
    }

    /**
     * Get the first element from the array.
     *
     * @return mixed
     */
    public function first()
    {
        if (empty($this->arr)) {
            return null;
        }

        if (!function_exists('array_key_first')) {
            return reset($this->arr);
        }

        return $this->arr[array_key_first($this->arr)];
    }

    /**
     * Get the first key of the given array.
     *
     * @return int|string
     */
    public function firstKey()
    {
        if (empty($this->arr)) {
            return null;
        }

        if (!function_exists('array_key_first')) {
            return key(reset($this->arr));
        }

        return array_key_first($this->arr);
    }

    /**
     * Get the last element of the array.
     *
     * @return mixed
     */
    public function last()
    {
        if (empty($this->arr)) {
            return null;
        }

        if (!function_exists('array_key_last')) {
            return end($this->arr);
        }

        return $this->arr[array_key_last($this->arr)];
    }

    /**
     * Get the last key of the given array.
     *
     * @return null|int|string
     */
    public function lastKey()
    {
        if (empty($this->arr)) {
            return null;
        }

        if (!function_exists('array_key_last')) {
            return key(end($this->arr));
        }
        
        return array_key_last($this->arr);
    }

    /**
     * Get the element located at the nth position in the given array.
     *
     * @param int $index
     *
     * @return null|int|string
     */
    public function nth(int $index)
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
     * @param int $index
     *
     * @return null|int|string
     */
    public function nthKey(int $index): null|int|string
    {
        if (empty($this->arr) || count($this->arr) < $index) {
            return null;
        }

        $sliced = array_slice($this->arr, $index, 1, true);

        if (!function_exists('array_key_first')) {
            return key(reset($sliced));
        }

        return array_key_first($sliced);
    }

    /**
     * Check if the given array path exists or not in the given array.
     *
     * @param string $path
     *
     * @return bool
     */
    public function pathExists(string $path): bool
    {
        $keys   =   explode('.', $path);
        $arr    =   $this->arr;
        $ref    =   &$arr;

        foreach ($keys as $key) {
            if (!array_key_exists($key, $ref) && !is_array($ref[$key])) {
                return false;
            }

            $ref = &$ref[$key];
        }

        return true;
    }

    /**
     * Check if the value of the given array path equals to NULL.
     *
     * @param string $path
     *
     * @return bool
     */
    public function pathNull(string $path): bool
    {
        $keys   =   explode('.', $path);
        $arr    =   $this->arr;
        $ref    =   &$arr;

        foreach ($keys as $key) {
            if (!isset($key, $ref) && !is_array($ref[$key])) {
                return true;
            }

            $ref = &$ref[$key];
        }

        return false;
    }

    /**
     * Check if the given array path exists & does not contain an "Empty Value".
     *
     * @param string $path
     *
     * @return bool
     */
    public function pathEmpty(string $path): bool
    {
        $arr        =   $this->arr;
        $ref        =   &$arr;
        $pathKeys   =   explode('.', $path);

        foreach ($pathKeys as $pathKey) {
            if (!isset($ref[$pathKey]) || empty($ref[$pathKey])) {
                return true;
            }

            $ref = &$ref[$pathKey];
        }

        return false;
    }

    /**
     * Get value of the specified array path from the given array.
     *
     * @param string $path
     *
     * @return mixed
     */
    public function get(string $path)
    {
        if (empty($this->arr)) {
            return null;
        }

        $ref    =   &$this->arr;
        $keys   =   explode('.', $path);

        foreach ($keys as $key) {
            if (!array_key_exists($key, $ref) || !is_array($ref)) {
                return null;
            }

            $ref = &$ref[$key];
        }

        return $ref;
    }

    /**
     * Get value of the given array path. If it does not exist OR is equal to NULL, return the given default value instead.
     *
     * @param   string  $path
     * @param   mixed   $default
     *
     * @return  mixed
     */
    public function getOrDefault(string $path, $default)
    {
        $value = null;

        if (empty($arr)) {
            return $value;
        }

        $ref    =   &$arr;
        $keys   =   explode('.', $path);

        foreach ($keys as $key) {
            if (!array_key_exists($key, $ref) || !is_array($ref)) {
                return $default;
            }

            $ref = &$ref[$key];
        }

        return $ref;
    }

    /**
     * Set value of the specified array keys path from the given array.
     *
     * @param   string  $path
     * @param   mixed   $value
     *
     * @return  static
     */
    public function set(string $path, $value): static
    {
        $arr        =   $this->arr;
        $ref        =   &$arr;
        $keys       =   explode('.', $path);
        $lastKey    =   array_pop($keys);

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

        $ref[$lastKey] = $value;  // Assign given value to the final nested key of the array.
        return $this->this($arr);
    }

    /**
     * Remove more than one array elements by the given array keys.
     *
     * @param string ...$paths
     *
     * @return static
     */
    public function remove(string ...$paths): static
    {
        $arr = $this->arr;

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

        return $this->this($arr);
    }

    /**
     * Apply the given callback function to both the array keys & array values.
     * 
     * @param   array                                           $arr
     * @param   callable(mixed $value, int|string $key): mixed  $fn
     *
     * @return  static
     */
    public function mapFn(array $arr, callable $fn): static
    {
        if (empty($arr)) {
            return $this;
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

        return $this->this($mapped);
    }

    /**
     * Remove all the empty values from the array.
     *
     * @return static
     */
    public function filter(): static
    {
        $this->arr = array_filter($this->arr);
        return $this;
    }

    /**
     * Filter elements of the array by applying the given callback function to each element.
     *
     * @param callable(mixed $val, int|string $key): bool
     * 
     * @return static
     */
    public function filterFn(callable $fn): static
    {
        $this->arr = array_filter($this->arr, $fn, ARRAY_FILTER_USE_BOTH);
        return $this;
    }

    /**
     * Filter out the first value from the array based on the given callback condition.
     *
     * @param   array                                           $arr
     * @param   callable(mixed $value, int|string $key): bool   $fn
     *
     * @return  mixed
     */
    public function firstFn(array $arr, callable $fn)
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
    public function lastFn(array $arr, callable $fn)
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
    public function isIndexed(array $arr): bool
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
    public function isAssociative(array $arr): bool
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
     * @return static
     */
    public function dot(array $arr): static
    {
        if (empty($arr)) {
            return $this;
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

        return $this->this($result);
    }

    /**
     * Convert the given dot path array to the nested one.
     *
     * @param array<string, mixed> $arr
     *
     * @return static
     */
    public function undot(array $arr): static
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

        return $this->this($result);
    }
}
