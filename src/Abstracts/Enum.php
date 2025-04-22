<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Abstracts;

use Iterator;
use ReflectionClass;

/**
 * @version 2.0
 */
class Enum extends NonInstantiable
{
    /**
     * Get a list of all of the constants defined in the current class.
     *
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        $transformed = function (array $arr): Iterator {
            foreach ($arr as $key => $value) {
                yield static::resolveName($key) => $value;
            }
        };

        return iterator_to_array($transformed(static::reflectionClass()->getConstants()));
    }

    /**
     * Get the names of all the constants defined in the current class.
     * 
     * @return array<int, string>
     */
    public static function keys(): array
    {
        $transformed = function (array $arr): Iterator {
            foreach ($arr as $key => $value) {
                yield static::resolveName($key);
            }
        };

        return iterator_to_array($transformed(array_keys(static::all())));
    }

    /**
     * Get values of all the constants defined in the current class.
     *
     * @return array<int, mixed>
     */
    public static function values(): array
    {
        return array_values(static::all());
    }

    /**
     * Get the name of the first constant whose value matches with the given parameters.
     *
     * @param   mixed   $val
     * @param   bool    $strict
     *
     * @return  null|string
     */
    public static function keyOf(mixed $val, bool $strict = true)
    {
        $all    =   static::all();
        $key    =   null;

        foreach ($all as $key => $value) {
            $result = $strict ? $value === $val : $value == $val;

            if ($result) {
                $key = static::resolveName($key);
            }
        }

        return $key;
    }

    /**
     * Get the names of all the constants whose values match with the given parameters.
     *
     * @param   mixed   $val
     * @param   bool    $strict
     *
     * @return array<int, string>
     */
    public static function keysOf(mixed $val, bool $strict = true)
    {
        $all    =   static::all();
        $keys   =   [];

        foreach ($all as $key => $value) {
            $result = $strict ? $value === $val : $value == $val;

            if ($result) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * Check whether or not the given constant exists in the current class.
     *
     * @param   string  $key
     * @param   bool    $upperCased
     *
     * @return  bool
     */
    public static function exists(string $key, bool $upperCased = true): bool
    {
        $transformedKey =   $upperCased ? strtoupper($key) : strtolower($key);
        $currentClass   =   static::class;

        return defined("$currentClass::{$transformedKey}") || defined("{$currentClass}::___{$transformedKey}");
    }

    /**
     * Get the value of the constant by the given name.
     *
     * If the null value is returned, it means that the constant does exist.
     *
     * @param   string  $key
     * @param   bool    $upperCased
     *
     * @return  mixed
     */
    public static function valueOf(string $key, bool $upperCased = true)
    {
        $transformedKey =   $upperCased ? strtoupper($key) : strtolower($key);
        $currentClass   =   static::class;

        if (defined("{$currentClass}::{$transformedKey}")) {
            return constant("{$currentClass}::{$transformedKey}");
        }

        if (defined("{$currentClass}::___{$transformedKey}")) {
            return constant("{$currentClass}::___{$transformedKey}");
        }

        return null;
    }

    /**
     * Get an instance of the '\ReflectionClass'.
     *
     * @return \ReflectionClass
     */
    final protected static function reflectionClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }

    /**
     * Resolve the name of the constant to its supposed form if required.
     *
     * @param string $name
     *
     * @return string
     */
    protected static function resolveName(string $name)
    {
        // Check if the given string starts with the special characters.
        if (static::strStartsWith($name, '___')) {
            return static::strAfter($name, '___');
        }

        return $name;
    }

    /**
     * Check if the given string starts with the specified substring.
     * 
     * @param   string  $str
     * @param   string  $sub
     * 
     * @return  bool
     */
    protected static function strStartsWith(string $str, string $sub)
    {
        if (function_exists('str_starts_with')) {
            return str_starts_with($str, $sub);
        }

        $initial = substr($str, 0, strlen($sub));

        if ($sub !== $initial) {
            return false;
        }

        return true;
    }

    /**
     * Get part of the string after the given substring.
     *
     * @param   string  $str
     * @param   string  $sub
     *
     * @return  null|string
     */
    protected function strAfter(string $str, string $sub)
    {
        $subPos = strpos($str, $sub);

        if ($subPos === false) {
            return null;
        }

        return substr($str, $subPos + strlen($sub));
    }
}
