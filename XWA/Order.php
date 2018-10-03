<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class Order implements Byteable {
	use HexDecoder;

	const ORDER_LENGTH = 0x95;

	public $Order;
	public $Throttle;
	public $Variable1;
	public $Variable2;
	public $Variable3;
	public $Unknown9;
	public $Target3Type;
	public $Target4Type;
	public $Target3;
	public $Target4;
	public $Target3OrTarget4;
	public $Target1Type;
	public $Target1;
	public $Target2Type;
	public $Target2;
	public $Target1OrTarget2;
	public $Speed;
	public $Waypoints;
	public $Unknown10;
	public $Unknown11;
	public $Unknown12;
	public $Unknown13;
	public $Unknown14;
	public function __construct($hex){
		$this->Order = $this->getByte($hex, 0x00);
		$this->Throttle = $this->getByte($hex, 0x01);
		$this->Variable1 = $this->getByte($hex, 0x02);
		$this->Variable2 = $this->getByte($hex, 0x03);
		$this->Variable3 = $this->getByte($hex, 0x04);
		$this->Unknown9 = $this->getByte($hex, 0x05);
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
		$this->Speed = $this->getByte($hex, 0x12);
		$this->Waypoints = array();
		for ($i = 0; $i < 8; $i++){
			$this->Waypoints[] = new Waypt(substr($hex, 0x14 + $i));
		}

		$this->Unknown10 = $this->getByte($hex, 0x72);
		$this->Unknown11 = $this->getBool($hex, 0x73);
		$this->Unknown12 = $this->getBool($hex, 0x74);
		$this->Unknown13 = $this->getBool($hex, 0x7B);
		$this->Unknown14 = $this->getBool($hex, 0x81);
	}

    public function getLength(){
        return self::ORDER_LENGTH;
    }
}