<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class TagBase extends PyriteBase implements Byteable {
	use HexDecoder;



	protected $TagLength = 0;

	/** @var SHORT */
	public $Length;
	/** @var CHAR<Length> */
	public $Text;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->Length = $this->getShort($hex, 0x0);
		if ($this->Length === 0) {
			$this->afterConstruct();
			return;
		}
		$this->Text = $this->getChar($hex, $this->Length, 0x2);
		$this->TagLength = $offset;
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			'Length' => $this->Length,
			'Text' => $this->Text
		];
	}

	protected function toHexString() {

		$hex = '';

		$offset = 0;
		$this->writeShort($hex, $this->Length, 0x0);
		if ($this->Length === 0) {
			return;
		}
		$this->writeChar($hex, $this->Text, 0x2, Length);
		return $hex;
	}


    public function getLength(){
        return $this->TagLength;
    }
}