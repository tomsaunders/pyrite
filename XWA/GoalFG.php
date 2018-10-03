<?php

namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class GoalFG implements Byteable {
	use HexDecoder;

	const GOALFG_LENGTH = 0x50;
	const POINT_MULTIPLIER = 25;

	public $Argument;
	public $Condition;
	public $Amount;
	public $Points;
	public $Enabled;
	public $Team;
	public $Unknown42;
	public $Parameter;
	public $ActiveSequence;
	public $Unknown15;

	public function __construct($hex) {

		$this->Argument       = $this->lookup(Constants::$FGGOAL_ARG, $hex, 0x00);
		$this->Condition      = $this->lookup(Constants::$CONDITION, $hex, 0x01);
		$this->Amount         = $this->lookup(Constants::$AMOUNT, $hex, 0x02);
		$this->Points         = $this->getSByte($hex, 0x03);
		$this->Enabled        = $this->getBool($hex, 0x04);
		$this->Team           = $this->getByte($hex, 0x05);
		$this->Unknown42      = $this->getByte($hex, 0x0D);
		$this->Parameter      = $this->getByte($hex, 0x0E);
		$this->ActiveSequence = $this->getByte($hex, 0x0F);
		$this->Unknown15      = $this->getBool($hex, 0x4F);
	}

	public function getLength() {
		return self::GOALFG_LENGTH;
	}

	public function getPoints() {
		return $this->Enabled && $this->Points ? $this->Points * self::POINT_MULTIPLIER : 0;
	}

	public function __toString() {
		if (!$this->Enabled) {
			return 'Disabled';
		}

		return "Goal $this->Argument $this->Condition $this->Amount " . $this->getPoints();
	}
}