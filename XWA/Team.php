<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class Team implements Byteable {
	use HexDecoder;

	const TEAM_LENGTH = 0x1E7;

	public $Reserved;
	public $Name;
	public $Allegiances;
	public $EndOfMissionMessages;
	public $Unknowns;
	public $EomVoiceIDs;
	public function __construct($hex){
		$this->Reserved = $this->getShort($hex, 0x000);
		$this->Name = $this->getString($hex, 0x002, 18);
		$this->Allegiances = array();
		for ($i = 0; $i < 10; $i++){
			$this->Allegiances[] = $this->getByte($hex, 0x01A + $i);
		}

		$this->EndOfMissionMessages = array();
		for ($i = 0; $i < 64; $i++){
			$this->EndOfMissionMessages[] = $this->getChar($hex, 0x024 + $i);
		}

		$this->Unknowns = array();
		for ($i = 0; $i < 6; $i++){
			$this->Unknowns[] = $this->getByte($hex, 0x1A4 + $i);
		}

		$this->EomVoiceIDs = array();
		for ($i = 0; $i < 20; $i++){
			$this->EomVoiceIDs[] = $this->getChar($hex, 0x1AA + $i);
		}

	}

    public function getLength(){
        return self::TEAM_LENGTH;
    }
}