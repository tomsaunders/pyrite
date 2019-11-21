<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class TriggerBase extends PyriteBase implements Byteable {
	use HexDecoder;

	const TRIGGER_LENGTH = 0x4;

	/** @var BYTE */
	public $Condition;
	/** @var BYTE */
	public $VariableType;
	/** @var BYTE */
	public $Variable;
	/** @var BYTE */
	public $TriggerAmount;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->Condition = $this->getByte($hex, 0x0);
		$this->VariableType = $this->getByte($hex, 0x1);
		$this->Variable = $this->getByte($hex, 0x2);
		$this->TriggerAmount = $this->getByte($hex, 0x3);
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"Condition" => $this->getConditionLabel(),
			"VariableType" => $this->getVariableTypeLabel(),
			"Variable" => $this->Variable,
			"TriggerAmount" => $this->getTriggerAmountLabel()		];
	}

        protected function getConditionLabel() {
            return isset($this->Condition) && isset(Constants::$CONDITION[$this->Condition]) ? Constants::$CONDITION[$this->Condition] : "Unknown";
        }

        protected function getVariableTypeLabel() {
            return isset($this->VariableType) && isset(Constants::$VARIABLETYPE[$this->VariableType]) ? Constants::$VARIABLETYPE[$this->VariableType] : "Unknown";
        }

        protected function getTriggerAmountLabel() {
            return isset($this->TriggerAmount) && isset(Constants::$TRIGGERAMOUNT[$this->TriggerAmount]) ? Constants::$TRIGGERAMOUNT[$this->TriggerAmount] : "Unknown";
        }

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeByte($hex, $this->Condition, 0x0);
		$this->writeByte($hex, $this->VariableType, 0x1);
		$this->writeByte($hex, $this->Variable, 0x2);
		$this->writeByte($hex, $this->TriggerAmount, 0x3);
		return $hex;
	}


    public function getLength(){
        return self::TRIGGER_LENGTH;
    }
}