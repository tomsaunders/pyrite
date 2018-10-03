<?php

namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class Trigger implements Byteable {
	use HexDecoder;

	const TRIGGER_LENGTH = 0x6;

	public $Condition;
	public $VariableType;
	public $Variable;
	public $Amount;
	public $Parameter;
	public $Parameter2;

	public function __construct($hex) {
		$this->Condition    = $this->lookup(Constants::$CONDITION, $hex, 0x0);
		$this->VariableType = $this->lookup(Constants::$VARIABLETYPE, $hex, 0x1);
		$this->Variable     = $this->getByte($hex, 0x2);
		$this->Amount       = $this->lookup(Constants::$AMOUNT, $hex, 0x3);
		$this->Parameter    = $this->getByte($hex, 0x4);
		$this->Parameter2   = $this->getByte($hex, 0x5);
	}

	public function getLength() {
		return self::TRIGGER_LENGTH;
	}
}