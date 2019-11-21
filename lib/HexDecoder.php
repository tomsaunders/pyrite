<?php

namespace Pyrite;

trait HexDecoder {
	// TODO remove all early returns and replace with checks to see why they're not being decoded properly
	public function getBool($chr, $startPos = NULL) {
		if ($startPos !== NULL) {
			$chr = substr($chr, $startPos, 1);
		}
		return ord($chr) ? TRUE : FALSE;
	}

	public function getSByte($chr, $startPos = NULL) {
		if ($startPos !== NULL) {
			$chr = substr($chr, $startPos, 1);
		}
		if (!$chr) return 0;
		return unpack('csbyte', $chr)['sbyte'];
	}

	public function getChar($chr, $startPos = 0, $length = 1) {
		$chr = substr($chr, $startPos, $length);
		return $this->printChar($chr);
		//        $chr = substr($chr, $startPos ? $startPos : 0, $length);
		//        return unpack('cchar', $chr)['char'];
	}

	public function printChar($char) {
		$bits = explode(chr(0), $char);
		return $bits[0];
	}

	public function getShort($str, $startPos = NULL) {
		if ($startPos !== NULL) {
			$str = substr($str, $startPos, 2);
		}
		if (!$str) {
			return 0;
		}
		return unpack('sshort', $str)['short'];
	}

	public function getInt($str, $startPos = NULL) {
		if ($startPos !== NULL) {
			$str = substr($str, $startPos, 4);
		}
		if (!$str) return 0;
		return unpack('lint', $str)['int'];
	}

	/**
	 * Get a string from the provided hex string. Terminate the string at the first null/chr(0) character
	 * @param     $str
	 * @param int $start  If set, perform a substr from this position before looking for the null characters
	 * @param int $length If set, perform a substr to this length from the start position
	 * @return string
	 */
	public function getString($str, $start = 0, $length = PHP_INT_MAX) {
		if ($start || $length) {
			$str = substr($str, $start, $length);
		}
		$bits = explode(chr(0), $str);
		return utf8_encode(trim($bits[0]));
	}

	public function lookup($array, $chr, $startPos = NULL) {
		$key = $this->getByte($chr, $startPos);
		if (!isset($array[$key])) {
			return "Unknown lookup $key";
		}
		return $array[$key];
	}

	public function getByte($chr, $startPos = NULL) {
		if ($startPos !== NULL) {
			$chr = substr($chr, $startPos, 1);
		}
		if (!$chr) {
			return 0;
		}
		return unpack('Cbyte', $chr)['byte'];
	}
}
