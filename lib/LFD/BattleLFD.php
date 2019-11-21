<?php
namespace Pyrite\LFD;

use Pyrite\Hex;

class BattleLFD extends LFD
{
    public $TextHeader;
    public $DeltHeader;
    public $BattleText;
    public $MapDelt;

    public function __construct($hex)
    {
        parent::__construct($hex);
		$this->TextHeader = new LFD(substr($hex, 16, 16));
        $this->DeltHeader = new LFD(substr($hex, 32, 16));
        $this->BattleText = new BattleTextLFD(substr($hex, 48));
//        $this->MapDelt = new DeltLFD(substr($hex, 64 + $this->BattleText->HeaderLength), $this->DeltHeader->HeaderLength);
    }

    public function __debugInfo()
    {
        return [
            'type' => $this->HeaderType,
            'name' => $this->HeaderName,
            'length' => $this->HeaderLength,
            'texthead' => $this->TextHeader,
            'delthead' => $this->DeltHeader,
            'text' => $this->BattleText,
            'delt' => $this->MapDelt
        ];
    }
}