<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class OrderBase extends PyriteBase implements Byteable {
	use HexDecoder;

	const ORDER_LENGTH = 0x12;

	/** @var BYTE */
	public $Order;
	/** @var BYTE */
	public $Throttle;
	/** @var BYTE */
	public $Variable1;
	/** @var BYTE */
	public $Variable2;
	/** @var BYTE */
	public $Unknown18;
	/** @var BYTE */
	public $Target3Type;
	/** @var BYTE */
	public $Target4Type;
	/** @var BYTE */
	public $Target3;
	/** @var BYTE */
	public $Target4;
	/** @var BOOL */
	public $Target3OrTarget4;
	/** @var BYTE */
	public $Target1Type;
	/** @var BYTE */
	public $Target1;
	/** @var BYTE */
	public $Target2Type;
	/** @var BYTE */
	public $Target2;
	/** @var BOOL */
	public $Target1OrTarget2;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->Order = $this->getByte($hex, 0x00);
		$this->Throttle = $this->getByte($hex, 0x01);
		$this->Variable1 = $this->getByte($hex, 0x02);
		$this->Variable2 = $this->getByte($hex, 0x03);
		$this->Unknown18 = $this->getByte($hex, 0x04);
		$this->Target3Type = $this->getByte($hex, 0x06);
		$this->Target4Type = $this->getByte($hex, 0x07);
		$this->Target3 = $this->getByte($hex, 0x08);
		$this->Target4 = $this->getByte($hex, 0x09);
		$this->Target3OrTarget4 = $this->getBool($hex, 0x0A);
		$this->Target1Type = $this->getByte($hex, 0x0C);
		$this->Target1 = $this->getByte($hex, 0x0D);
		$this->Target2Type = $this->getByte($hex, 0x0E);
		$this->Target2 = $this->getByte($hex, 0x0F);
		$this->Target1OrTarget2 = $this->getBool($hex, 0x10);
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"Order" => $this->getOrderLabel(),
			"Throttle" => $this->Throttle,
			"Variable1" => $this->Variable1,
			"Variable2" => $this->Variable2,
			"Unknown18" => $this->Unknown18,
			"Target3Type" => $this->getTarget3TypeLabel(),
			"Target4Type" => $this->getTarget4TypeLabel(),
			"Target3" => $this->Target3,
			"Target4" => $this->Target4,
			"Target3OrTarget4" => $this->Target3OrTarget4,
			"Target1Type" => $this->getTarget1TypeLabel(),
			"Target1" => $this->Target1,
			"Target2Type" => $this->getTarget2TypeLabel(),
			"Target2" => $this->Target2,
			"Target1OrTarget2" => $this->Target1OrTarget2		];
	}

        protected function getOrderLabel() {
            return isset($this->Order) && isset(Constants::$ORDER[$this->Order]) ? Constants::$ORDER[$this->Order] : "Unknown";
        }

        protected function getTarget3TypeLabel() {
            return isset($this->Target3Type) && isset(Constants::$VARIABLETYPE[$this->Target3Type]) ? Constants::$VARIABLETYPE[$this->Target3Type] : "Unknown";
        }

        protected function getTarget4TypeLabel() {
            return isset($this->Target4Type) && isset(Constants::$VARIABLETYPE[$this->Target4Type]) ? Constants::$VARIABLETYPE[$this->Target4Type] : "Unknown";
        }

        protected function getTarget1TypeLabel() {
            return isset($this->Target1Type) && isset(Constants::$VARIABLETYPE[$this->Target1Type]) ? Constants::$VARIABLETYPE[$this->Target1Type] : "Unknown";
        }

        protected function getTarget2TypeLabel() {
            return isset($this->Target2Type) && isset(Constants::$VARIABLETYPE[$this->Target2Type]) ? Constants::$VARIABLETYPE[$this->Target2Type] : "Unknown";
        }

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeByte($hex, $this->Order, 0x00);
		$this->writeByte($hex, $this->Throttle, 0x01);
		$this->writeByte($hex, $this->Variable1, 0x02);
		$this->writeByte($hex, $this->Variable2, 0x03);
		$this->writeByte($hex, $this->Unknown18, 0x04);
		$this->writeByte($hex, $this->Target3Type, 0x06);
		$this->writeByte($hex, $this->Target4Type, 0x07);
		$this->writeByte($hex, $this->Target3, 0x08);
		$this->writeByte($hex, $this->Target4, 0x09);
		$this->writeBool($hex, $this->Target3OrTarget4, 0x0A);
		$this->writeByte($hex, $this->Target1Type, 0x0C);
		$this->writeByte($hex, $this->Target1, 0x0D);
		$this->writeByte($hex, $this->Target2Type, 0x0E);
		$this->writeByte($hex, $this->Target2, 0x0F);
		$this->writeBool($hex, $this->Target1OrTarget2, 0x10);
		return $hex;
	}


    public function getLength(){
        return self::ORDER_LENGTH;
    }
}