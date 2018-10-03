<?php
namespace Pyrite;

trait HexDecoder
{
    public function getBool($chr, $startPos = null)
    {
        if ($startPos !== null) {
            $chr = substr($chr, $startPos, 1);
        }
        return ord($chr) ? true : false;
    }

    public function getByte($chr, $startPos = null)
    {
        if ($startPos !== null) {
            $chr = substr($chr, $startPos, 1);
        }
        return unpack('Cbyte', $chr)['byte'];
    }

    public function getSByte($chr, $startPos = null)
    {
        if ($startPos !== null) {
            $chr = substr($chr, $startPos, 1);
        }
        return unpack('csbyte', $chr)['sbyte'];
    }

    public function getChar($chr, $length, $startPos = 0)
    {
        return substr($chr, $startPos, $length);
        //        $chr = substr($chr, $startPos ? $startPos : 0, $length);
        //        return unpack('cchar', $chr)['char'];
    }

    public function getShort($str, $startPos = null)
    {
        if ($startPos !== null) {
            $str = substr($str, $startPos, 2);
        }
        return unpack('sshort', $str)['short'];
    }

    public function getInt($str, $startPos = null)
    {
        if ($startPos !== null) {
            $str = substr($str, $startPos, 4);
        }
        return unpack('lint', $str)['int'];
    }

    /**
     * Get a string from the provided hex string. Terminate the string at the first null/chr(0) character
     * @param $str
     * @param int $start   If set, perform a substr from this position before looking for the null characters
     * @param int $length  If set, perform a substr to this length from the start position
     * @return string
     */
    public function getString($str, $start = 0, $length = PHP_INT_MAX)
    {
        if ($start || $length) {
            $str = substr($str, $start, $length);
        }
        $end = strpos($str, chr(0), 1);
        if ($end) {
            $str = substr($str, 0, $end);
        }
        return trim($str);
    }

    public function lookup($array, $chr, $startPos = null)
    {
        $key = $this->getByte($chr, $startPos);
        if (!isset($array[$key])) {
            return "Unknown lookup $key";
        }
        return $array[$key];
    }

    public function printChar($char)
    {
        $bits = explode(chr(0), $char);
        return $bits[0];
    }
}
