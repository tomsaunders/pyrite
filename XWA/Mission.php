<?php

namespace Pyrite\XWA;

use Pyrite\Hex;
use Pyrite\MissionFormat;

class Mission extends MissionFormat {
	const GLOBAL_GOAL_COUNT = 10;

	function __construct($source, $justHeader = FALSE) {
		if (strlen($source) < 100) {
			if (!file_exists($source)) {
				$source .= '.tie';
			}
			$hex            = file_get_contents($source);
			$this->filename = $source;
		} else {
			$hex = $source;
		}

		$remaining = $this->readHeader($hex);

		if (!$justHeader) {
			$remaining = $this->readFlightGroups($remaining, $this->header->NumFGs);
			$remaining = $this->readMessages($remaining, $this->header->NumMessages);
			$remaining = $this->readGlobalGoals($remaining, self::GLOBAL_GOAL_COUNT);

//			FileHeader
//			FlightGroup[NumFGs]
//			Message[NumMessages]
//			GlobalGoal[10]
//			Team[10]
//			Briefing[2]
//			STR(0x187C)		EditorNotes
//			STR(100)[128]		BriefingStringNotes
//			STR(100)[64]		MessageNotes
//			STR(100)[10,3]			EomNotes
//			0xFA0		Unknown
//			STR(100)[3]		DescriptionNotes
//			STR(*)[NumFGs,8,3]	FGGoalStrings
//			STR(*)[10,3,4,3]	GlobalGoalStrings
//			0x1E0		Unknown
//			STR(*)[192,16]		OrderStrings
//			STR(4096)[3]		Descriptions
		}
	}

	protected function readHeader($hex) {
		$this->header = new FileHeader($hex);
		return substr($hex, $this->header->getLength());
	}

	protected function readFlightGroups($hex, $count) {
		for ($i = 0; $i < $count; $i++){
			$fg = new FlightGroup($hex);
			$hex = substr($hex, $fg->getLength());
			$this->flightGroups[] = $fg;
		}
		return $hex;
	}

	protected function readMessages($hex, $count) {
		for ($i = 0; $i < $count; $i++){
			$msg = new Message($hex);
			$hex = substr($hex, $msg->getLength());
			$this->messages[] = $msg;
		}
		return $hex;
	}

	protected function readGlobalGoals($hex, $count) {
		for ($i = 0; $i < $count; $i++){
			$gg = new GlobalGoal($hex);
			$hex = substr($hex, $gg->getLength());
			$this->globalGoals[] = $gg;
		}
	}

	function printDump() {
		return [
			'filename' => $this->filename,
			'header'   => $this->header->printDump(),
		];
	}

	public function valid() {
		return TRUE;
	}
}