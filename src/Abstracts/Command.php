<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Abstracts;

use Exception;

/**
 * @version 1.0
 */
abstract class Command
{
    /**
     * Execute the given and return its result.
     *
     * @return bool
     */
    abstract public function execute(): bool;

    /**
     * Get the raw arguments that were passed to the command.
     *
     * @return array<int, string>
     */
    protected function getRawArguments(): array
    {
        if (!$this->areArgumentsProvided()) {
            return [];
        }

        return array_slice($_SERVER['argv'], 1);
    }

    /**
     * Simplify the structure of the arguments that were passed to the command.
     *
     * @return array<int|string, string>
     */
    protected function getArguments(): array
    {
        $args   =   $this->getRawArguments();
        $result =   [];

        foreach ($args as $arg) {
            if (!$this->strStartsWith($arg, '--')) {
                throw new Exception("The arguments provided to the command must start with the sign '--'.");
            }

            if ($this->strContains($arg, '=')) {
                $result[$this->strBetween($arg, '--', '=')] = $this->strAfter($arg, '=');
                continue;
            }

            $result[] = $arg;
        }

        return $result;
    }

    /**
     * Get the total number of arguments that were passed to the command.
     *
     * @return int
     */
    protected function countArguments(): int
    {
        if (!$this->areArgumentsProvided()) {
            return 0;
        }

        return $_SERVER['argc'] - 1;
    }

    /**
     * Check if any arguments were provided to the command.
     *
     * @return bool
     */
    protected function areArgumentsProvided(): bool
    {
        if (!isset($_SERVER['argv'])) {
            return false;
        }

        if (empty($_SERVER['argv'])) {
            return false;
        }

        if (count($_SERVER['argv']) < 2) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given string starts with the specified substring.
     * 
     * @param   string  $str
     * @param   string  $sub
     * 
     * @return  bool
     */
    protected function strStartsWith(string $str, string $sub): bool
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
     * Check if the given substring is part of the string or not.
     *
     * @param   string  $str
     * @param   string  $sub
     *
     * @return  bool
     */
    protected function strContains(string $str, string $sub)
    {
        if (function_exists('str_contains')) {
            return str_contains($str, $sub);
        }

        return strpos($str, $sub) !== false;
    }

    /**
     * Get part of the string which lies between the given two strings.
     *
     * @param   string  $str
     * @param   string  $start
     * @param   string  $end
     *
     * @return  null|string
     */
    protected function strBetween(string $str, string $before, string $after): null|string
    {
        // Obtain the positions of start & end parts of the given string to obtain our desired substring.
        $start = strpos($str, $before);

        // If either of those positions are not found, there's no need to proceed further.
        if ($start === false) {
            return null;
        }

        $end = strpos($str, $after);

        if ($end === false) {
            return null;
        }

        // Get the position of the last character of the '$before' string.
        $start += strlen($before);

        // Determine the length of the substring that we want to extract present between the '$before' & '$after' string.
        $len = strpos($str, $after, $start) - $start;

        // Finally, return the substring which is present between the '$before' & '$after' string.
        return substr($str, $start, $len);
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
