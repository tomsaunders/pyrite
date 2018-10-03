<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class Briefing implements Byteable {
	use HexDecoder;

	const BRIEFING_LENGTH = 0x0;

	public $RunningTime;
	public $Unknown1;
	public $StartLength;
	public $EventsLength;
	public $Events;
	public $ShowToTeams;
	public $Tags;
	public $Strings;
	public function __construct($hex){
		$this->RunningTime = $this->getShort($hex, 0x0000);
		$this->Unknown1 = $this->getShort($hex, 0x0002);
		$this->StartLength = $this->getShort($hex, 0x0004);
		$this->EventsLength = $this->getInt($hex, 0x0006);
		$this->Events = new Event(substr($hex, 0x000A));
		$this->ShowToTeams = array();
		for ($i = 0; $i < 10; $i++){
			$this->ShowToTeams[] = $this->getBool($hex, 0x440A + $i);
		}

		$this->Tags = array();
		for ($i = 0; $i < 128; $i++){
			$this->Tags[] = new Tag(substr($hex, 0x4414 + $i));
		}

		$this->Strings = array();
		for ($i = 0; $i < 128; $i++){
			$this->Strings[] = new String(substr($hex, 0x8000 + $i));
		}

	}

    public function getLength(){
        return self::BRIEFING_LENGTH;
    }
}