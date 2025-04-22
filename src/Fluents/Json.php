<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Fluents;

use Iterator;
use Exception;

/**
 * @version 1.0
 */
class Json
{
    /**
     * @var array<string, int> $options
     */
    protected array $options;

    /**
     * @var array<int|string, mixed> $data
     */
    protected array $data;

    /**
     * Initialize the JSON so that further operations can be performed on it.
     *
     * @param   string                      $json
     * @param   array<string, int|string>   $options
     *
     * @throws  \Exception
     */
    public function __construct(string $json, array $options = [])
    {
        $options    =   $this->validateOptions($options);
        $result     =   json_decode($json, true, $options['depth']);
        $code       =   json_last_error();

        if ($code !== JSON_ERROR_NONE) {
            throw new Exception(json_last_error_msg(), $code);
        }

        $this->data     =   $result;
        $this->options  =   $options;
    }

    /**
     * Validate the options and/or arguments passed when 'Encoding/Decoding' the JSON.
     *
     * @param array<int|string, mixed> $options
     *
     * @return array<int|string, mixed>
     */
    protected function validateOptions(array $options)
    {
        $options = [
            'depth' =>  $options['depth'] ?? 1024,
            'flags' =>  $options['flags'] ?? 0
        ];

        if (!filter_var($this->options['depth'], FILTER_VALIDATE_INT)) {
            throw new Exception('[Developer][Exception]: The option "depth" must be an integer value.');
        }

        if (!filter_var($this->options['flags'], FILTER_VALIDATE_INT)) {
            throw new Exception('[Developer][Exception]: The option "flags" must be an integer value.');
        }

        return $options;
    }

    /**
     * Set a value at the given path in the JSON data.
     *
     * @param   string  $dotPath
     * @param   mixed   $value
     *
     * @return  static
     */
    public function set(string $dotPath, $value): static
    {
        $ref        =   &$this->data;
        $keys       =   explode('.', $dotPath);
        $lastKey    =   array_pop($keys);

        foreach ($keys as $key) {
            /**
             * If the key specified in the given path does not exist or is not an array,
             * set it to an empty array. This way, we'll keep going inside the
             * nested structure of the array, until the last key.
             */
            if (!isset($ref[$key]) || !is_array($ref[$key])) {
                $ref[$key] = [];
            }

            $ref = &$ref[$key];
        }

        $ref[$lastKey] = $value;  // Assign given value to the final nested key of the array.
        return $this;
    }

    /**
     * Set many values at inside the JSON based on the given data.
     *
     * @param array<int|string, mixed> $data
     *
     * @return static
     */
    public function setMany(array $data): static
    {
        foreach ($data as $path => $value) {
            $ref        =   &$this->data;
            $keys       =   explode('.', $path);
            $lastKey    =   array_pop($keys);

            foreach ($keys as $key) {
                /**
                 * If the key specified in the given path does not exist or is not an array,
                 * set it to an empty array. This way, we'll keep going inside the
                 * nested structure of the array, until the last key.
                 */
                if (!isset($ref[$key]) || !is_array($ref[$key])) {
                    $ref[$key] = [];
                }

                $ref = &$ref[$key];
            }

            $ref[$lastKey] = $value;  // Assign given value to the final nested key of the array.
        }

        return $this;
    }

    /**
     * Get a value from JSON data based on the given dot path.
     *
     * @param string $dotPath
     *
     * @return mixed
     */
    public function get(string $path)
    {
        if (empty($this->data)) {
            return null;
        }

        $ref    =   &$this->data;
        $keys   =   explode('.', $path);

        foreach ($keys as $key) {
            if (!is_array($ref) || !array_key_exists($key, $ref)) {
                return null;
            }

            $ref = &$ref[$key];
        }

        return $ref;
    }

    /**
     * Get values from the JSON based on the given array paths.
     *
     * @param string ...$paths
     *
     * @return array
     */
    public function getMany(string ...$paths): array
    {
        $generator = function (string ...$paths): Iterator {
            foreach ($paths as $path) {
                yield $path => $this->get($path);
            };
        };

        return iterator_to_array($generator($paths));
    }

    /**
     * Return the JSON data in its encoded form.
     *
     * @return null|string
     */
    public function toJson(array $options = [])
    {
        $options    =   $this->validateOptions($options);
        $result     =   json_encode($this->data, $options['flags'], $options['depth']);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $result;
    }

    /**
     * Return the JSON data as either an array or an object.
     *
     * @param bool $asArr
     *
     * @return null|array
     */
    public function toArr()
    {
        if (empty($this->data)) {
            return null;
        }

        return $this->data;
    }

    /**
     * Decode the JSON to an object.
     *
     * @param array<string, mixed> $options
     *
     * @return null|\stdClass
     */
    public function toObj(array $options = [])
    {
        if (empty($this->data)) {
            return null;
        }

        $options    =   $this->validateOptions($options);
        $json       =   json_encode($this->data, $options['flags'], $options['depth']);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        $obj = json_decode($json, false, $options['depth'], $options['flags']);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $obj;
    }

    /**
     * Make the error message based on the given parameters.
     *
     * @param   int     $errorCode
     * @param   string  $errorMessage
     *
     * @return  null|string
     */
    protected function makeErrorMessage(int $errorCode, string $errorMessage)
    {
        if ($errorCode === 0) {
            return null;
        }

        $validErrors = [
            JSON_ERROR_UTF8                     =>  'JSON_ERROR_UTF8',
            JSON_ERROR_DEPTH                    =>  'JSON_ERROR_DEPTH',
            JSON_ERROR_UTF16                    =>  'JSON_ERROR_UTF16',
            JSON_ERROR_SYNTAX                   =>  'JSON_ERROR_SYNTAX',
            JSON_ERROR_CTRL_CHAR                =>  'JSON_ERROR_CTRL_CHAR',
            JSON_ERROR_RECURSION                =>  'JSON_ERROR_RECURSION',
            JSON_ERROR_INF_OR_NAN               =>  'JSON_ERROR_INF_OR_NAN',
            JSON_ERROR_STATE_MISMATCH           =>  'JSON_ERROR_STATE_MISMATCH',
            JSON_ERROR_UNSUPPORTED_TYPE         =>  'JSON_ERROR_UNSUPPORTED_TYPE',
            JSON_ERROR_INVALID_PROPERTY_NAME    =>  'JSON_ERROR_INVALID_PROPERTY_NAME',
        ];

        return "[{$validErrors[$errorCode]}]: {$errorMessage}";
    }
}
