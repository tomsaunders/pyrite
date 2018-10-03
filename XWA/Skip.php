<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class Skip implements Byteable {
	use HexDecoder;

	const SKIP_LENGTH = 0x10;

	public $Trigger1;
	public $Trigger2;
	public $Trigger1OrTrigger2;
	public function __construct($hex){
		$this->Trigger1 = new Trigger(substr($hex, 0x0));
		$this->Trigger2 = new Trigger(substr($hex, 0x6));
		$this->Trigger1OrTrigger2 = $this->getBool($hex, 0xE);
	}

    public function getLength(){
        return self::SKIP_LENGTH;
    }
}