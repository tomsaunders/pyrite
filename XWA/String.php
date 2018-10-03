<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class String implements Byteable {
	use HexDecoder;

	const STRING_LENGTH = 0x0;

	public $Length;
	public $Label;
	public function __construct($hex){
		$this->Length = $this->getShort($hex, 0x0);
		$this->Label = $this->getChar($hex, 0x2);
	}

    public function getLength(){
        return self::STRING_LENGTH;
    }
}