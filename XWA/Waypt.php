<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class Waypt implements Byteable {
	use HexDecoder;

	const WAYPT_LENGTH = 0x8;

	public $X;
	public $Y;
	public $Z;
	public $Enabled;
	public function __construct($hex){
		$this->X = $this->getShort($hex, 0x0);
		$this->Y = $this->getShort($hex, 0x2);
		$this->Z = $this->getShort($hex, 0x4);
		$this->Enabled = $this->getBool($hex, 0x6);
	}

    public function getLength(){
        return self::WAYPT_LENGTH;
    }
}