<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class GoalFGBase extends PyriteBase implements Byteable {
	use HexDecoder;

	const GOALFG_LENGTH = 0x2;

	/** @var BYTE */
	public $Condition;
	/** @var BYTE */
	public $GoalAmount;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->Condition = $this->getByte($hex, 0x0);
		$this->GoalAmount = $this->getByte($hex, 0x1);
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"Condition" => $this->getConditionLabel(),
			"GoalAmount" => $this->getGoalAmountLabel()		];
	}

        protected function getConditionLabel() {
            return isset($this->Condition) && isset(Constants::$CONDITION[$this->Condition]) ? Constants::$CONDITION[$this->Condition] : "Unknown";
        }

        protected function getGoalAmountLabel() {
            return isset($this->GoalAmount) && isset(Constants::$GOALAMOUNT[$this->GoalAmount]) ? Constants::$GOALAMOUNT[$this->GoalAmount] : "Unknown";
        }

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeByte($hex, $this->Condition, 0x0);
		$this->writeByte($hex, $this->GoalAmount, 0x1);
		return $hex;
	}


    public function getLength(){
        return self::GOALFG_LENGTH;
    }
}