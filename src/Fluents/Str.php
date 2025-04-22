<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Fluents;

/**
 * @version 1.0
 */
class Str
{
    /**
     * @var string $str
     */
    protected string $str;

    /**
     * @var bool $isImmutable
     */
    protected bool $isImmutable = false;

    /**
     * @param   string  $str
     * @param   array   $options
     */
    public function __construct(string $str, array $options = [])
    {
        $this->str          =   $str;
        $this->isImmutable  =   (bool) ($options['immutable'] ?? false);
    }

    /**
     * Return an appropriate class instance depending on whether the immutable mode is turned on or off.
     *
     * @return static
     */
    protected function this(string $str): static
    {
        if ($this->isImmutable) {
            return new static($str);
        }

        $this->str = $str;
        return $this;
    }

    /**
     * Get part of the string present before the specified substring.
     *
     * @param string $sub
     *
     * @return null|static
     */
    public function before(string $sub)
    {
        $sub = strstr($this->str, $sub, true);

        if ($sub === false) {
            return null;
        }

        return $this->this($sub);
    }

    /**
     * [Case Insensitive]: Get part of the string present before the specified substring.
     *
     * @param string $sub
     *
     * @return null|static
     */
    public function beforeInsensitive(string $sub)
    {
        $sub = stristr($this->str, $sub, true);

        if ($sub === false) {
            return null;
        }

        return $this->this($sub);
    }

    /**
     * Get part of the string after the given substring.
     *
     * @param string $sub
     *
     * @return null|static
     */
    public function after(string $sub)
    {
        $subPos = strpos($this->str, $sub);

        if ($subPos === false) {
            return null;
        }

        $subLength  =   $subPos + strlen($sub);
        $sub        =   substr($this->str, $subLength);

        return $this->this($sub);
    }

    /**
     * [Case Insensitive]: Get part of the string after the given substring.
     *
     * @param string $sub
     *
     * @return null|static
     */
    public function afterInsensitive(string $sub)
    {
        $subPos = stripos($this->str, $sub);

        if ($subPos === false) {
            return null;
        }

        $subLength  =   $subPos + strlen($sub);
        $sub        =   substr($this->str, $subLength);

        return $this->this($sub);
    }

    /**
     * Get part of the substring before the last occurrence of the given substring.
     *
     * @param string $sub
     *
     * @return null|static
     */
    public function beforeLast(string $sub)
    {
        $subPos = strrpos($this->str, $sub);

        if ($subPos === false) {
            return null;
        }

        $sub = substr($this->str, 0, $subPos);
        return $this->this($sub);
    }

    /**
     * [Case Insensitive]: Get part of the substring before the last occurrence of the given substring.
     *
     * @param string $sub
     *
     * @return null|static
     */
    public function beforeLastInsensitive(string $sub)
    {
        $subPos = strripos($this->str, $sub);

        if ($subPos === false) {
            return null;
        }

        $sub = substr($this->str, 0, $subPos);
        return $this->this($sub);
    }

    /**
     * Get part of the substring after the last occurrence of the given substring.
     *
     * @param string $sub
     *
     * @return null|static
     */
    public function afterLast(string $sub)
    {
        $subPos = strpos($this->str, $sub);

        if ($subPos === false) {
            return null;
        }

        $subLength  =   $subPos + strlen($sub);
        $sub        =   substr($this->str, $subLength);

        return $this->this($sub);
    }

    /**
     * [Case Insensitive]: Get part of the substring after the last occurrence of the given substring.
     *
     * @param string $sub
     *
     * @return null|static
     */
    public function afterLastInsensitive(string $sub)
    {
        $subPos = stripos($this->str, $sub);

        if ($subPos === false) {
            return null;
        }

        $subLength  =   $subPos + strlen($sub);
        $sub        =   substr($this->str, $subLength);

        return $this->this($sub);
    }

    /**
     * Get part of the string which lies between the given two strings.
     *
     * @param   string  $start
     * @param   string  $end
     *
     * @return  null|static
     */
    public function between(string $before, string $after)
    {
        // Obtain the positions of start & end parts of the given string to obtain our desired substring.
        $start = strpos($this->str, $before);

        // If either of those positions are not found, there's no need to proceed further.
        if ($start === false) {
            return null;
        }

        $end = strpos($this->str, $after);

        if ($end === false) {
            return null;
        }

        // Get the position of the last character of the '$before' string.
        $start += strlen($before);

        // Determine the length of the substring that we want to extract present between the '$before' & '$after' string.
        $len = strpos($this->str, $after, $start) - $start;

        // Finally, return the substring which is present between the '$before' & '$after' string.
        return $this->this(substr($this->str, $start, $len));
    }

    /**
     * [Case Insensitive]: Get part of the string which lies between the given two strings.
     *
     * @param   string  $start
     * @param   string  $end
     *
     * @return  null|static
     */
    public function betweenInsensitive(string $before, string $after)
    {
        // Obtain the positions of start & end parts of the given string to obtain our desired substring.
        $start = stripos($this->str, $before);

        // If either of those positions are not found, there's no need to proceed further.
        if ($start === false) {
            return null;
        }

        $end = stripos($this->str, $after);

        if ($end === false) {
            return null;
        }

        // Figure out the position of the last character of the first part of the string.
        $start += strlen($before);

        // Finally, return the part of the string that lies between the index of
        // the last character of the before part of the string & the index of
        // the first character of teh after part of the string.
        return $this->this(substr($this->str, $start, $end));
    }

    /**
     * Check if the given substring is part of the string or not.
     *
     * @param   string  $str
     * @param   string  $sub
     *
     * @return  bool
     */
    public function contains(string $sub)
    {
        if (function_exists('str_contains')) {
            return str_contains($this->str, $sub);
        }

        return strpos($this->str, $sub) !== false;
    }

    /**
     * [Case Insensitive]: Check if the given substring is part of the string or not.
     *
     * @param   string  $str
     * @param   string  $sub
     *
     * @return  bool
     */
    public function containsInsensitive(string $sub)
    {
        return stripos($this->str, $sub) !== false;
    }

    /**
     * Replace the given search string with the given replace string.
     *
     * @param   string  $search
     * @param   string  $replace
     *
     * @return  string
     */
    public function replace(string $search, string $replace)
    {
        $pos = strpos($this->str, $search);

        if ($pos === false) {
            return $this->this($this->str);
        }

        return $this->this(substr_replace($this->str, $replace, $pos, strlen($search)));
    }

    /**
     * [Case Insensitive]: Replace the given search string with the given replace string.
     *
     * @param   string  $search
     * @param   string  $replace
     *
     * @return  string
     */
    public function replaceInsensitive(string $search, string $replace)
    {
        $pos = stripos($this->str, $search);

        if ($pos === false) {
            return $this->this($this->str);
        }

        return $this->this(substr_replace($this->str, $replace, $pos, strlen($search)));
    }

    /**
     * Replace the given search string with the given replace string.
     *
     * @param   string  $search
     * @param   string  $replace
     *
     * @return  string
     */
    public function replaceLast(string $search, string $replace)
    {
        $pos = strrpos($this->str, $search);

        if ($pos === false) {
            return $this->this($this->str);
        }

        return $this->this(substr_replace($this->str, $replace, $pos, strlen($search)));
    }

    /**
     * [Case Insensitive]: Replace the given search string with the given replace string.
     *
     * @param   string  $search
     * @param   string  $replace
     *
     * @return  string
     */
    public function replaceLastInsensitive(string $search, string $replace)
    {
        $pos = strripos($this->str, $search);

        if ($pos === false) {
            return $this->this($this->str);
        }

        return $this->this(substr_replace($this->str, $replace, $pos, strlen($search)));
    }

    /**
     * Replace specified substrings with their replacements.
     *
     * @param   array<int, string>  $searches
     * @param   array<int, string>  $replacements
     *
     * @return  static
     */
    public function replaceMany(array $searches, array $replacements)
    {
        return $this->this((string) str_replace($this->str, $searches, $replacements));
    }

    /**
     * [Case Insensitive]: Replace specified substrings with their replacements.
     *
     * @param   array<int, string>  $searches
     * @param   array<int, string>  $replacements
     *
     * @return  string
     */
    public function replaceManyInsensitive(array $searches, array $replacements)
    {
        return $this->this(str_ireplace($this->str, $searches, $replacements));
    }

    /**
     * Replace all the occurrences of the given substring.
     *
     * @param   string  $search
     * @param   string  $replace
     *
     * @return  string
     */
    public function replaceEach(string $search, string $replace)
    {
        $str = '';

        while (true) {
            $pos = strpos($this->str, $search);

            if ($pos === false) {
                break;
            }

            $str = substr_replace($this->str, $replace, $pos, strlen($search));
        }

        return $this->this($str);
    }

    /**
     * [Case Insensitive]: Replace all the occurrences of the given substring.
     *
     * @param   string  $search
     * @param   string  $replace
     *
     * @return  string
     */
    public function replaceEachInsensitive(string $search, string $replace)
    {
        $str = '';

        while (true) {
            $pos = stripos($this->str, $search);

            if ($pos === false) {
                break;
            }

            $str = substr_replace($this->str, $replace, $pos, strlen($search));
        }

        return $this->this($str);
    }

    /**
     * Check if the given string starts with the specified substring.
     * 
     * @param string $sub
     * 
     * @return bool
     */
    public function startsWith(string $sub)
    {
        if (function_exists('str_starts_with')) {
            return str_starts_with($this->str, $sub);
        }

        $initial = substr($this->str, 0, strlen($sub));

        if ($sub !== $initial) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given string ends with the specified substring.
     * 
     * @param string $sub
     * 
     * @return bool
     */
    public function endsWith(string $sub)
    {
        if (function_exists('str_ends_with')) {
            return str_ends_with($this->str, $sub);
        }

        $last = substr($this->str, -1, strlen($sub));

        if ($sub !== $last) {
            return false;
        }

        return true;
    }
}
