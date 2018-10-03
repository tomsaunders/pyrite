<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class Tag implements Byteable {
	use HexDecoder;

	const TAG_LENGTH = 0x0;

	public $Length;
	public $Label;
	public function __construct($hex){
		$this->Length = $this->getShort($hex, 0x0);
		$this->Label = $this->getChar($hex, 0x2);
	}

    public function getLength(){
        return self::TAG_LENGTH;
    }
}