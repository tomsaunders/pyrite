<?php

namespace Pyrite;

class PyriteBase implements \JsonSerializable {
	public $TIE;
	public $diffLimit = FALSE;

	public $hex;

	public function jsonSerialize() {
		return $this->__debugInfo();
	}

	public function __debugInfo() {

	}

	public function compareHex($otherHex) {
		return $this->hex === $otherHex;
	}

	protected function afterConstruct() {

	}
}
