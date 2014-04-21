<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 20/03/14
 * Time: 9:00 PM
 */

trait HexDecoder {
    public function getBool($chr, $startPos = null){
        if ($startPos !== null){
            $chr = substr($chr, $startPos, 1);
        }
        return ord($chr) ? TRUE : FALSE;
    }

    public function getByte($chr, $startPos = null){
        if ($startPos !== null){
            $chr = substr($chr, $startPos, 1);
        }
        return unpack('Cbyte', $chr)['byte'];
    }

    public function getSByte($chr, $startPos = null){
        if ($startPos !== null){
            $chr = substr($chr, $startPos, 1);
        }
        return unpack('csbyte', $chr)['sbyte'];
    }

    public function getChar($chr){
        return unpack('cchar', $chr)['char'];
    }

    public function getShort($str, $startPos = null){
        if ($startPos !== null){
            $str = substr($str, $startPos, 2);
        }
        return unpack('sshort', $str)['short'];
    }

    public function getInt($str, $startPos = null){
        if ($startPos !== null){
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
    public function getString($str, $start = 0, $length = null){
        if ($start || $length){
            $str = substr($str, $start, $length);
        }
        if ($end = strpos($str, chr(0))){
            $str = substr($str, 0, $end);
        }
        return $str;
    }
} 