<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class GlobalCargo implements Byteable {
	use HexDecoder;

	const GLOBALCARGO_LENGTH = 0x8C;

	public $Cargo;
	public $Unknown1;
	public $Unknown2;
	public $Unknown3;
	public $Unknown4;
	public $Unknown5;
	public function __construct($hex){
		$this->Cargo = array();
		$this->Cargo[] = $this->getString($hex, 0x00, 64);

		$this->Unknown1 = $this->getBool($hex, 0x44);
		$this->Unknown2 = $this->getByte($hex, 0x48);
		$this->Unknown3 = $this->getByte($hex, 0x49);
		$this->Unknown4 = $this->getByte($hex, 0x4A);
		$this->Unknown5 = $this->getByte($hex, 0x4B);
	}

    public function getLength(){
        return self::GLOBALCARGO_LENGTH;
    }
}