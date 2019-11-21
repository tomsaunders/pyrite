<?php
namespace Pyrite\TIE;

class TriggerOld
{
    public $condition;
    public $type;
    public $ID;
    public $var;
    public $amount;

    private $variable;
    private $varByte;
    private $print;
    private $TIE;

    /**
     * @param $byteString
     * @param Mission $TIE
     * struct Trigger (size 0x4)
    {
    0x0	BYTE	Condition (enum)
    0x1	BYTE	VariableType (enum)
    0x2	BYTE	Variable
    0x3	BYTE	TriggerAmount (enum)
    }
     */
    public function __construct($byteString, Mission $TIE)
    {
        $this->TIE = $TIE;

        $this->condition = lookup(Constants::$TRIGGER_CONDITIONS, $byteString[0]);
        $this->type = lookup(Constants::$TRIGGER_VALUE_TYPE, $byteString[1]);
        //		$this->ID			= getByte($byteString[2]);
        $this->var = getByte($byteString[2]);
        error_log(__METHOD__ . ' t ' . $this->type . ' v ' . $this->var . ' b ' . printHex(substr($byteString, 0, 4)));
        $this->varByte = $byteString[3];
        $this->amount = lookup(Constants::$TRIGGER_AMOUNT, $byteString[3]);
    }

    private function calculateVariable()
    {
        switch ($this->type) {
            case 'Flight Group':
                return $this->TIE->getFGString($this->var);
            case 'Ship Type':
                return lookup(Constants::$SHIP_TYPE, chr(ord($this->varByte) + 1));
            case 'Ship Category':
                return lookup(Constants::$SHIP_CATEGORY, $this->varByte);
            case 'Object Category':
                return lookup(Constants::$OBJECT_CATEGORY, $this->varByte);
            case 'Assignment':
                return "--I dont know how to look up {$this->type}";
            case 'Craft When':
                return lookup(Constants::$TRIGGER_CRAFT_WHEN, $this->var);
            case 'IFF':
                return 'IFF ' . $this->TIE->IFF->getIndex($this->var);
            case 'Global Group':
                return "{$this->var}: (" . $this->TIE->getGG($this->var) . ')';
            case 'General':
                return lookup(Constants::$TRIGGER_GENERAL, $this->varByte);
            default:
                return "--I dont know how to look up {$this->type}";
        }
    }

    public function fgCount()
    {
        switch ($this->type) {
            case 'Flight Group':
                return count($this->TIE->getFG($this->var));
            case 'IFF':
                $iff = $this->TIE->IFF->getIndex($this->var);
                $fgs = $this->TIE->getFGsByIFF($iff);
                return array_sum(array_map('count', $fgs));
            default:
                return 20; //guess
        }
    }

    public function getFG()
    {
        if ($this->type === 'Flight Group') {
            return $this->TIE->getFG($this->var);
        }
        return false;
    }

    public function hasData()
    {
        return ($this->condition !== 'None' && $this->condition !== 'Always');
    }

    public function __toString()
    {
        if (!empty($this->print)) {
            return $this->print;
        }
        if ($this->condition !== 'None') {
            $this->variable = $this->calculateVariable();
            $this->print = implode(' ', array(
                $this->amount,
                'of',
                $this->type,
                $this->variable,
                'must be',
                $this->condition
            ));
        } else {
            $this->print = 'None';
        }
        return $this->print;
    }
}
