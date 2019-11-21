<?php

namespace Pyrite\TIE;

use Pyrite\Byteable;

class GoalMessages implements Byteable {
    public $primaryA;
    public $primaryB;
    public $secondA;
    public $secondB;
    public $failA;
    public $failB;

    const MESSAGE_COUNT = 6;
    const MESSAGE_LENGTH = 64;

    public function __construct($hex){
        $this->primaryA = getString($hex, self::MESSAGE_LENGTH * 0, self::MESSAGE_LENGTH);
        $this->primaryB = getString($hex, self::MESSAGE_LENGTH * 1, self::MESSAGE_LENGTH);
        $this->secondA  = getString($hex, self::MESSAGE_LENGTH * 2, self::MESSAGE_LENGTH);
        $this->secondB  = getString($hex, self::MESSAGE_LENGTH * 3, self::MESSAGE_LENGTH);
        $this->failA    = getString($hex, self::MESSAGE_LENGTH * 4, self::MESSAGE_LENGTH);
        $this->failB    = getString($hex, self::MESSAGE_LENGTH * 5, self::MESSAGE_LENGTH);
    }

    public function getLength(){
        return self::MESSAGE_COUNT * self::MESSAGE_LENGTH;
    }

    public function __toString(){
        $a = array(
            'Primary Goal #1' => $this->primaryA,
            'Primary Goal #2' => $this->primaryB
        );
        if (!empty($this->secondA)){
            $a['Secondary Goal #1'] = $this->secondA;
            $a['Secondary Goal #2'] = $this->secondB;
        }
        $a['Failure #1'] = $this->failA;
        $a['Failure #2'] = $this->failB;
        $str = "\n";
        foreach($a as $k => $m) $str .= "\t$k: $m\n";

        return $str;
    }
} 