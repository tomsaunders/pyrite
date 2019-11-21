<?php

namespace Pyrite\LFD;

use Pyrite\Byteable;
use Pyrite\Hex;
use Pyrite\HexDecoder;

class TextLFD extends LFD {
	public $NumberOfStrings; // number of missions + 4
	public $Strings = [];

	public function __construct($hex) {
		parent::__construct($hex);
		$this->NumberOfStrings = $this->getShort($hex, 0x10);
		$off                   = 0x12;
		for ($i = 0; $i < $this->NumberOfStrings; $i++) {
			$str             = new LFDString(substr($hex, $off));
			$off             += $str->getLength();
			$this->Strings[] = $str;
		}
	}

	public function __debugInfo() {
		return [
			'type'            => $this->HeaderType,
			'name'            => $this->HeaderName,
			'length'          => $this->HeaderLength,
			'NumberOfStrings' => $this->NumberOfStrings,
			'Strings'         => $this->Strings,
		];
	}

}

class LFDString implements Byteable, \ArrayAccess {
	use HexDecoder;

	public $Length; // total length of all SubStrings and the final Reserved value.
	public $SubStrings = []; // Null terminated
	public $Reserved; // 0x00
	public $Hex;

	public function __construct($hex) {
		$this->Length = $this->getShort($hex);
		if ($this->Length > 1) {
			$this->SubStrings = explode(chr(0), substr($hex, 2, $this->Length - 2));
		}
		$this->Reserved = $this->getByte($hex, $this->Length + 1);
		$this->Hex      = Hex::hexToStr(substr($hex, 2, $this->Length - 1));
	}

	public function getLength() {
		return $this->Length + 2;
	}

	public function offsetExists($offset) {
		return isset($this->SubStrings[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->SubStrings[$offset]) ? $this->SubStrings[$offset] : '';
	}

	public function offsetSet($offset, $value) {
		$this->SubStrings[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->SubStrings[$offset]);
	}
}