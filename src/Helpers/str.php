<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils;

/**
 * Get part of the string present before the specified substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  null|string
 */
function strBefore(string $str, string $sub)
{
    $sub = strstr($str, $sub, true);

    if ($sub === false) {
        return null;
    }

    return $sub;
}

/**
 * [Case Insensitive]: Get part of the string present before the specified substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  null|string
 */
function strBeforeInsensitive(string $str, string $sub)
{
    $sub = stristr($str, $sub, true);

    if ($sub === false) {
        return null;
    }

    return $sub;
}

/**
 * Get part of the string after the given substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  null|string
 */
function strAfter(string $str, string $sub)
{
    $subPos = strpos($str, $sub);

    if ($subPos === false) {
        return null;
    }

    return substr($str, $subPos + strlen($sub));
}

/**
 * [Case Insensitive]: Get part of the string after the given substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  null|string
 */
function strAfterInsensitive(string $str, string $sub)
{
    $subPos = stripos($str, $sub);

    if ($subPos === false) {
        return null;
    }

    return substr($str, $subPos + strlen($sub));
}

/**
 * Get part of the substring before the last occurrence of the given substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  null|string
 */
function strBeforeLast(string $str, string $sub)
{
    $subPos = strrpos($str, $sub);

    if ($subPos === false) {
        return null;
    }

    return substr($str, 0, $subPos);
}

/**
 * [Case Insensitive]: Get part of the substring before the last occurrence of the given substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  null|string
 */
function strBeforeLastInsensitive(string $str, string $sub)
{
    $subPos = strripos($str, $sub);

    if ($subPos === false) {
        return null;
    }

    return substr($str, 0, $subPos);
}

/**
 * Get part of the substring after the last occurrence of the given substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  null|string
 */
function strAfterLast(string $str, string $sub)
{
    $subPos = strpos($str, $sub);

    if ($subPos === false) {
        return null;
    }

    return substr($str, $subPos + strlen($sub));
}

/**
 * [Case Insensitive]: Get part of the substring after the last occurrence of the given substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  null|string
 */
function strAfterLastInsensitive(string $str, string $sub)
{
    $subPos = stripos($str, $sub);

    if ($subPos === false) {
        return null;
    }

    return substr($str, $subPos + strlen($sub));
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
function strBetween(string $str, string $before, string $after): null|string
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
 * [Case Insensitive]: Get part of the string which lies between the given two strings.
 *
 * @param   string  $str
 * @param   string  $start
 * @param   string  $end
 *
 * @return  null|string
 */
function strBetweenInsensitive(string $str, string $before, string $after): null|string
{
    // Obtain the positions of start & end parts of the given string to obtain our desired substring.
    $start = stripos($str, $before);

    // If either of those positions are not found, there's no need to proceed further.
    if ($start === false) {
        return null;
    }

    $end = stripos($str, $after);

    if ($end === false) {
        return null;
    }

    // Figure out the position of the last character of the first part of the string.
    $start += strlen($before);

    // Finally, return the part of the string that lies between the index of
    // the last character of the before part of the string & the index of
    // the first character of teh after part of the string.
    return substr($str, $start, $end);
}

/**
 * Check if the given substring is part of the string or not.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  bool
 */
function strContains(string $str, string $sub)
{
    if (function_exists('str_contains')) {
        return str_contains($str, $sub);
    }

    return strpos($str, $sub) !== false;
}

/**
 * [Case Insensitive]: Check if the given substring is part of the string or not.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  bool
 */
function strContainsInsensitive(string $str, string $sub)
{
    return stripos($str, $sub) !== false;
}

/**
 * Replace the given search string with the given replace string.
 *
 * @param   string  $str
 * @param   string  $search
 * @param   string  $replace
 *
 * @return  string
 */
function strReplace(string $str, string $search, string $replace)
{
    $pos = strpos($str, $search);

    if ($pos === false) {
        return $str;
    }

    return substr_replace($str, $replace, $pos, strlen($search));
}

/**
 * [Case Insensitive]: Replace the given search string with the given replace string.
 *
 * @param   string  $str
 * @param   string  $search
 * @param   string  $replace
 *
 * @return  string
 */
function strReplaceInsensitive(string $str, string $search, string $replace)
{
    $pos = stripos($str, $search);

    if ($pos === false) {
        return $str;
    }

    return substr_replace($str, $replace, $pos, strlen($search));
}

/**
 * Replace the given search string with the given replace string.
 *
 * @param   string  $str
 * @param   string  $search
 * @param   string  $replace
 *
 * @return  string
 */
function strReplaceLast(string $str, string $search, string $replace)
{
    $pos = strrpos($str, $search);

    if ($pos === false) {
        return $str;
    }

    return substr_replace($str, $replace, $pos, strlen($search));
}

/**
 * [Case Insensitive]: Replace the given search string with the given replace string.
 *
 * @param   string  $str
 * @param   string  $search
 * @param   string  $replace
 *
 * @return  string
 */
function strReplaceLastInsensitive(string $str, string $search, string $replace)
{
    $pos = strripos($str, $search);

    if ($pos === false) {
        return $str;
    }

    return substr_replace($str, $replace, $pos, strlen($search));
}

/**
 * Replace specified substrings with their replacements.
 *
 * @param   string              $str
 * @param   array<int, string>  $searches
 * @param   array<int, string>  $replacements
 *
 * @return  string
 */
function strReplaceMany(string $str, array $searches, array $replacements)
{
    return str_replace($str, $searches, $replacements);
}

/**
 * [Case Insensitive]: Replace specified substrings with their replacements.
 *
 * @param   string              $str
 * @param   array<int, string>  $searches
 * @param   array<int, string>  $replacements
 *
 * @return  string
 */
function strReplaceManyInsensitive(string $str, array $searches, array $replacements)
{
    return str_ireplace($str, $searches, $replacements);
}

/**
 * Replace all the occurrences of the given substring.
 *
 * @param   string  $str
 * @param   string  $search
 * @param   string  $replace
 *
 * @return  string
 */
function strReplaceEach(string $str, string $search, string $replace)
{
    while (true) {
        $pos = strpos($str, $search);

        if ($pos === false) {
            break;
        }

        $str = substr_replace($str, $replace, $pos, strlen($search));
    }

    return $str;
}

/**
 * [Case Insensitive]: Replace all the occurrences of the given substring.
 *
 * @param   string  $str
 * @param   string  $search
 * @param   string  $replace
 *
 * @return  string
 */
function strReplaceEachInsensitive(string $str, string $search, string $replace)
{
    while (true) {
        $pos = stripos($str, $search);

        if ($pos === false) {
            break;
        }

        $str = substr_replace($str, $replace, $pos, strlen($search));
    }

    return $str;
}

/**
 * Check if the given string starts with the specified substring.
 * 
 * @param   string  $str
 * @param   string  $sub
 * 
 * @return  bool
 */
function strStartsWith(string $str, string $sub)
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
 * Check if the given string ends with the specified substring.
 * 
 * @param   string  $str
 * @param   string  $sub
 * 
 * @return  bool
 */
function strEndsWith(string $str, string $sub)
{
    if (function_exists('str_ends_with')) {
        return str_ends_with($str, $sub);
    }

    $last = substr($str, -1, strlen($sub));

    if ($sub !== $last) {
        return false;
    }

    return true;
}

/**
 * Generate a string containing random characters.
 *
 * @param int $length
 *
 * @return string
 */
function strRandom(int $length = 12): string
{
    $randomBytes = function_exists('random_bytes')
        ? random_bytes($length)
        : openssl_random_pseudo_bytes($length);

    return base64_encode($randomBytes);
}

/**
 * Generate a UUID4 string.
 *
 * @return string
 */
function strUuid4(): string
{
    $data       =   random_bytes(16);
    $data[6]    =   chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8]    =   chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
