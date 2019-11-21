<?php

namespace Pyrite;

class HexEncoder implements Byteable {
	private $hex = '';

	public function pad($length, $filler = NULL) {
		$filler = isset($filler) ? $filler : chr(0);
		return $this->put(str_pad('', $length, $filler));
	}

	private function put($out) {
		$this->hex .= $out;
		return $this;
	}

	public function putBool($value) {
		return $this->put($value ? 1 : 0);
	}

	public function putByte($byte) {
		return $this->put(pack('C', $byte));
	}

	public function putSByte($byte) {
		return $this->put(pack('c', $byte));
	}

	public function putChar($chr, $length = 0, $filler = NULL) {
		if (!$filler) {
			$filler = chr(0);
		}
		if ($length) {
			$chr = str_pad($chr, $length, $filler);
		}
		return $this->put($chr);
	}

	public function putShort($short) {
		return $this->put(pack('s', $short));
	}

	public function putInt($int) {
		return $this->put(pack('l', $int));
	}

	public function putString($chr, $length = 0, $filler = NULL) {
		if (!$filler) {
			$filler = chr(0);
		}
		if ($length) {
			$chr = str_pad($chr, $length, $filler);
		}
		return $this->put($chr . chr(0));
	}

	public function getString($str, $start = 0, $length = PHP_INT_MAX) {
		if ($start || $length) {
			$str = substr($str, $start, $length);
		}
		$end = strpos($str, chr(0), 1);
		if ($end) {
			$str = substr($str, 0, $end);
		}
		return trim($str);
	}

	public function __toString() {
		return $this->hex;
	}

	public function getLength() {
		return strlen($this->hex);
	}
}