<?php

namespace Pyrite;

class Hex {
    public static function render($hex, $asCells = false){
        echo "<pre>" . self::hexToStr($hex, $asCells) . "</pre>";
    }

    public static function hexToStr($hex, $asCells = false){
        $prefix = $asCells ? '<td>'  : '';
        $suffix = $asCells ? '</td>' : ' ';
        $count = strlen($hex);
        if ($count < 32){
            $hexStr = '';
            $str = '';
            for ($i = 0; $i < $count; $i++){
                $ord = ord($hex[$i]);
                $hexStr .= sprintf("$prefix%02X$suffix", $ord);
                $str .= ($ord < 30 ? '.' : $hex[$i]);
            }
            return "$hexStr $prefix $str $suffix";
            //single line
        } else {
            //split to multiple lines
            $hexStr = '';
            $str = '';
            $lines = array('', "pos| #0 #1 #2 #3 #4 #5 #6 #7 #8 #9 10 11 12 13 14 15", '--------------------------------------------------------------------------');
            $checkSum = 0;
            for ($i = 0; $i < $count; $i++){
                if ($i > 0 && $i % 16 === 0){
                    $pos = ((count($lines) - 3) * 16);
                    $line = sprintf("%03d| ", $pos) . "$hexStr $prefix $str $suffix";
                    $line .= sprintf("| %X", $checkSum);
                    $lines[] = $line;
                    $hexStr = '';
                    $str = '';
                    $checkSum = 0;
                }
                $ord = ord($hex[$i]);
                $checkSum += $ord;
                $hexStr .= sprintf("$prefix%02X$suffix", $ord);
                $str .= ($ord < 30 ? '.' : $hex[$i]);
            }
            if ($str !== ''){
                $pos = ((count($lines) - 3) * 16);
                $line = sprintf("%03d| ", $pos) . "$hexStr $prefix $str $suffix";
                $line .= sprintf("| %X", $checkSum);
                $lines[] = $line;
            }
            return implode("\n", $lines);
        }
    }
} 