<?php

namespace Pyrite\EHM;

class Decoder {
	const EHM_EXT = '.ehm';
	const EHB = 'Battle.ehb';
	private $header = array();
	private $rot = 0;
	private $missions = array();
	private $TIEs = array();

	function __construct($file){
		$extracted = $this->extract($file);
		$this->readHeader($extracted);
		$this->convertMissions($extracted);
	}

	function convertMissions($path){
		foreach ($this->missions as $mission){
			$mission = trim($mission);
			$get = $path . DIRECTORY_SEPARATOR . $mission;
			$put = $path . DIRECTORY_SEPARATOR . 'converted' . $mission;
			$ehm = file_get_contents($get);
			for ($i = 0; $i < strlen($ehm); $i++){
				$value 		= unpack('c', $ehm[$i])[1];
				$decrypt 	= $this->rot ^ $value;
				$ehm[$i] = pack('c', $decrypt);
			}
			file_put_contents($put, $ehm);
			$tie = new \TIE($ehm);
			$this->TIEs[] = $tie->header;
		}
	}

	function extract($file){
		$zip = new \ZipArchive();
		$zip->open($file);
		$path = str_replace(self::EHM_EXT, '', $file);
		$zip->extractTo($path);
		$zip->close();
		return $path;
	}

	function readHeader($path){
		$ehb = file_get_contents($path . DIRECTORY_SEPARATOR . self::EHB);
		$hex = $ehb;
		$ehb = array();
		$ehb['unk1'] = ord($hex[1]);
		$ehb['title'] = substr($hex, 2, 50);
		$ehb['#'] = ord($hex[53]);
		$ehb['unk2'] = ord($hex[54]);
		$ehb['missions'] = array();
		$remaining = substr($hex, 55);
		for ($m = 0; $m < $ehb['#']; $m++){
			$ehb['missions'][] = substr($remaining, 20 * $m, 20);
		}
		$remaining = substr($remaining, 21 * $ehb['#']);
		$ehb['rem'] = \Hex::hexToStr($remaining);
		for ($r = 0; $r < strlen($remaining); $r++){
			$ehb['r' . $r] = ord($remaining[$r]);
		}

		$this->header = $ehb;
		$this->header['path'] = $path;
		$this->rot = ord($hex[1]);
		$this->missions = $ehb['missions'];
	}

	function debugOutput(){
		return array($this->header, $this->missions, $this->TIEs);
	}
} 