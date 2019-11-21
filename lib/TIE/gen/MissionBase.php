<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class MissionBase extends PyriteBase implements Byteable {
	use HexDecoder;

	public $MissionLength = 0;

	/** @var FileHeader */
	public $FileHeader;
	/** @var FlightGroup */
	public $FlightGroups;
	/** @var Message */
	public $Messages;
	/** @var GlobalGoal */
	public $GlobalGoals;
	/** @var Briefing */
	public $Briefing;
	/** @var PreMissionQuestions */
	public $PreMissionQuestions;
	/** @var PostMissionQuestions */
	public $PostMissionQuestions;
	/** @var BYTE */
	public $End; // Reserved(0xFF)

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->FileHeader = new FileHeader(substr($hex, 0x000));;
		$offset = 0x000;
		$offset += $this->FileHeader->getLength();

        $this->FlightGroups = [];
        
        for ($i = 0; $i < $this->FileHeader->NumFGs; $i++) {
            $t = new FlightGroup(substr($hex, $offset));;
            $this->FlightGroups[] = $t;
            $offset += $t->getLength();
        }

        $this->Messages = [];
        
        for ($i = 0; $i < $this->FileHeader->NumMessages; $i++) {
            $t = new Message(substr($hex, $offset));;
            $this->Messages[] = $t;
            $offset += $t->getLength();
        }

        $this->GlobalGoals = [];
        
        for ($i = 0; $i < 3; $i++) {
            $t = new GlobalGoal(substr($hex, $offset));;
            $this->GlobalGoals[] = $t;
            $offset += $t->getLength();
        }
		$this->Briefing = new Briefing(substr($hex, $offset));;
		$offset += $this->Briefing->getLength();

        $this->PreMissionQuestions = [];
        
        for ($i = 0; $i < 10; $i++) {
            $t = new PreMissionQuestions(substr($hex, $offset));;
            $this->PreMissionQuestions[] = $t;
            $offset += $t->getLength();
        }

        $this->PostMissionQuestions = [];
        
        for ($i = 0; $i < 10; $i++) {
            $t = new PostMissionQuestions(substr($hex, $offset));;
            $this->PostMissionQuestions[] = $t;
            $offset += $t->getLength();
        }
		$this->End = $this->getByte($hex, $offset);
		$offset += 1;
		$this->MissionLength = $offset;
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"FileHeader" => $this->FileHeader,
			"FlightGroups" => $this->FlightGroups,
			"Messages" => $this->Messages,
			"GlobalGoals" => $this->GlobalGoals,
			"Briefing" => $this->Briefing,
			"PreMissionQuestions" => $this->PreMissionQuestions,
			"PostMissionQuestions" => $this->PostMissionQuestions,
			"End" => $this->End		];
	}

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeObject($hex, $this->FileHeader, 0x000);
		$offset = 0x000;
		$offset += $this->FileHeader->getLength();

        $offset = $offset;
        for ($i = 0; $i < $this->FileHeader->NumFGs; $i++) {
            $t = $this->FlightGroups[$i];
            $this->writeObject($hex, $this->FlightGroups[$i], $offset);
            $offset += $t->getLength();
        }

        $offset = $offset;
        for ($i = 0; $i < $this->FileHeader->NumMessages; $i++) {
            $t = $this->Messages[$i];
            $this->writeObject($hex, $this->Messages[$i], $offset);
            $offset += $t->getLength();
        }

        $offset = $offset;
        for ($i = 0; $i < 3; $i++) {
            $t = $this->GlobalGoals[$i];
            $this->writeObject($hex, $this->GlobalGoals[$i], $offset);
            $offset += $t->getLength();
        }
		$this->writeObject($hex, $this->Briefing, $offset);

        $offset = $offset;
        for ($i = 0; $i < 10; $i++) {
            $t = $this->PreMissionQuestions[$i];
            $this->writeObject($hex, $this->PreMissionQuestions[$i], $offset);
            $offset += $t->getLength();
        }

        $offset = $offset;
        for ($i = 0; $i < 10; $i++) {
            $t = $this->PostMissionQuestions[$i];
            $this->writeObject($hex, $this->PostMissionQuestions[$i], $offset);
            $offset += $t->getLength();
        }
		$this->writeByte($hex, $this->End, $offset);
		$offset += 1;
		return $hex;
	}


    public function getLength(){
        return $this->MissionLength;
    }
}