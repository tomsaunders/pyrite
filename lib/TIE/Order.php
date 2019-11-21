<?php
namespace Pyrite\TIE;

class Order extends OrderBase
{
    public $diffLimit = true;

    public function __toString()
    {
        $out = [$this->getOrderLabel()];
        if ($this->Target1) {
            $lookup = $this->lookupVariable($this->Target1Type, $this->Target1);
            $out[] = "{$this->getTarget1TypeLabel()} {$this->Target1} [$lookup]";
            if ($this->Target2) {
                if ($this->Target1OrTarget2) {
                    $out[] = "OR";
                }
                $lookup = $this->lookupVariable($this->Target2Type, $this->Target2);
                $out[] = "{$this->getTarget2TypeLabel()} {$this->Target2} [$lookup]";
            }
        }

        if ($this->Target3) {
            $out[] = "THEN"; // TODO or? and? then?
            $lookup = $this->lookupVariable($this->Target3Type, $this->Target3);
            $out[] = "{$this->getTarget3TypeLabel()} {$this->Target3} [$lookup]";
            if ($this->Target4) {
                if ($this->Target3OrTarget4) {
                    $out[] = "OR";
                }
                $lookup = $this->lookupVariable($this->Target4Type, $this->Target4);
                $out[] = "{$this->getTarget4TypeLabel()} {$this->Target4} [$lookup]";
            }
        }

        return implode(" ", $out);
    }

    protected function lookupVariable($type, $var)
    {
        //TODO move to mission
        switch ($type) {
            case 0:
                return '';
            case 1:
                $fg = $this->TIE->FlightGroups[$var];
                $fg->TIE = $this->TIE;
                return (string) $fg;
            case 2:
                return Constants::$CRAFTTYPE[$var];
            case 3:
                return Constants::$CRAFTCATEGORY[$var];
            case 4:
                return Constants::$OBJECTCATEGORY[$var];
            case 5:
                return $this->TIE->lookupIFF($var);
            case 6:
                return Constants::$ORDER[$var];
            case 7:
                return Constants::$CRAFTWHEN[$var];
            case 8:
                return $this->TIE->lookupGlobalGroup($var);
            case 9:
                return Constants::$MISC[$var];
            case 10:
                return 'Unknown';
        }
    }
}
