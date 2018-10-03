<?php
namespace Pyrite\XvT;

use Pyrite\Hex;
use Pyrite\MissionFormat;

//use PyrXvT\Header;

class Mission extends MissionFormat {

	const GLOBAL_GOAL_COUNT = 10;

	function __construct($source, $justHeader = FALSE){
		if (strlen($source) < 100){
			$hex = file_get_contents($source);
			$this->filename = $source;
		} else {
			$hex = $source;
		}
		$remaining = $this->readHeader($hex);

		if (!$justHeader){
			$remaining = $this->readFlightGroups($remaining, $this->header->NumFGs);
			$remaining = $this->readMessages($remaining, $this->header->NumMessages);
			$remaining = $this->readGlobalGoals($remaining, self::GLOBAL_GOAL_COUNT);
		}
	}

	public function valid(){
		return $this->header->isValid();
	}


    protected function readHeader($hex){
        $this->header = new FileHeader($hex);
        return substr($hex, $this->header->getLength());
    }

	protected function readFlightGroups($hex, $count){
		for ($i = 0; $i < $count; $i++){
			$fg = new FlightGroup($hex, $i+1);
			$hex = substr($hex, $fg->getLength());
			$this->flightGroups[] = $fg;
		}
		return $hex;
	}

	protected function readMessages($hex, $count){
		for ($i = 0; $i < $count; $i++){
			$msg = new Message($hex);
			$hex = substr($hex, $msg->getLength());
			$this->messages[] = $msg;
		}
		return $hex;
	}

	protected function readGlobalGoals($hex, $count){
		for ($i = 0; $i < $count; $i++){
			$gg = new GlobalGoal($hex);
			$hex = substr($hex, $gg->getLength());
			$this->globalGoals[] = $gg;
		}
		return $hex;
	}

    function printDump(){
        return array(
            'filename' => $this->filename,
//            'header'=> $this->header,
//			'fgs' 	=> array_map('strval', $this->flightGroups),
//			'goals' => array_map('strval', $this->flightGroups[0]->Goals),
//			'msgs' 	=> array_map('strval', $this->messages),
			'gg' 	=> array_map('strval', $this->globalGoals),
			$this->globalGoals[0]
        );
    }
}