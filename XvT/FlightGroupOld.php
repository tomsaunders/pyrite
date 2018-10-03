<?php

namespace Pyrite\XvT;

use Pyrite\Byteable;
use Pyrite\Hex;
use Pyrite\HexDecoder;
use Pyrite\IFlightGroup;

class FlightGroupOld implements Byteable, \Countable, IFlightGroup{
	use HexDecoder;

	const FLIGHT_GROUP_LENGTH = 1378;
	const ROLE_COUNT = 4;

	public $Name;
	/** @var ShipType */
	private $ShipType;

	private $waves;
	private $perWave;

	private $playerNumber;
	private $playerCraft;

	private $IFF;
	private $team;

	public function __construct($hex){
		$this->Name = $this->getString($hex, 0, 20);
		for ($i = 0; $i < self::ROLE_COUNT; $i++){
			//parse role; 0x014	Role[4]
		}
		$cargo   = $this->getString($hex, 40, 20);
		$special = $this->getString($hex, 60, 20);
//	0x050	BYTE	SpecialCargoCraft
//	0x051	BOOL	RandomSpecialCargo
		$this->ShipType = new ShipType($this->getByte($hex, 0x52));
		$this->perWave = $this->getByte($hex, 0x53);
//	0x054	BYTE	Status1 (enum)
//	0x055	BYTE	Warhead (enum)
//	0x056	BYTE	Beam (enum)
		$this->IFF  = $this->getByte($hex, 0x57);
		$this->team = $this->getByte($hex, 0x58);
//	0x058	BYTE	Team
//	0x059	BYTE	GroupAI (enum)
//	0x05A	BYTE	Markings (enum)
//	0x05B	BYTE	Radio (enum)
//	0x05D	BYTE	Formation (enum)
//	0x05E	BYTE	FormationSpacing
//	0x05F	BYTE	GlobalGroup
//	0x060	BYTE	LeaderSpacing
		$this->waves = $this->getByte($hex, 0x61);
//	0x061	BYTE	NumberOfWaves
//	0x062	BYTE	Unknown1
//	0x063	BOOL	Unknown2
		$this->playerNumber = $this->getByte($hex, 0x64);
		$this->playerCraft = $this->getByte($hex, 0x66);
//	0x064	BYTE	PlayerNumber
//	0x065	BYTE	ArriveOnlyIfHuman
//	0x066	BYTE	PlayerCraft
//	0x067	BYTE	Yaw
//	0x068	BYTE	Pitch
//	0x069	BYTE	Roll
//	0x06D	BYTE	ArrivalDifficulty (enum)
		$this->difficulty = $this->lookup(Constants::$ARRIVALDIFFICULTY, $hex, 0x6D);
//	0x06E	Trigger	Arrival1
//	0x072	Trigger	Arrival2
//	0x078	BOOL	Arrival1OrArrival2
//	0x079	Trigger	Arrival3
//	0x07D	Trigger	Arrival4
//	0x083	BOOL	Arrival3OrArrival4
//	0x084	BOOL	Arrival12OrArrival34
//	0x085	BYTE	Unknown3				[ARR_DELAY_30SECONDS?]
//	0x086	BYTE	ArrivalDelayMinutes
//	0x087	BYTE	ArrivalDelaySeconds
//	0x088	Trigger	Departure1
//	0x08C	Trigger	Departure2
//	0x092	BOOL	Departure1OrDeparture2
//	0x093	BYTE	DepartureDelayMinutes
//	0x094	BYTE	DepartureDelaySeconds
//	0x095	BYTE	AbortTrigger (enum)
//	0x096	BYTE	Unknown4
//	0x098	BYTE	Unknown5
//	0x09A	BYTE	ArrivalMothership
//	0x09B	BYTE	ArriveViaMothership
//	0x09C	BYTE	AlternateArrivalMothership
//	0x09D	BYTE	AlternateArriveViaMothership
//	0x09E	BYTE	DepartureMothership
//	0x09F	BYTE	DepartViaMothership
//	0x0A0	BYTE	AlternateDepartureMothership
//	0x0A1	BYTE	AlternatDepartViaMothership
//	0x0A2	Order[4]
//	0x1EA	Trigger[2] SkipToOrder4
//	0x1F4	BOOL	Skip1OrSkip2
//	0x1F5	GoalFG[8]
//	0x466	Waypt[4]
//	0x516	BOOL	Unknown17
//	0x518	BOOL	Unknown18
//	0x520	BOOL	Unknown19
//	0x521	BYTE	Unknown20
//	0x522	BYTE	Unknown21
//	0x523	BYTE	Countermeasures
//	0x524	BYTE	CraftExplosionTime
//	0x525	BYTE	Status2 (enum)
//	0x526	BYTE	GlobalUnit
//	0x527	BOOL	Unknown22
//	0x528	BOOL	Unknown23
//	0x529	BOOL	Unknown24
//	0x52A	BOOL	Unknown25
//	0x52B	BOOL	Unknown26
//	0x52C	BOOL	Unknown27
//	0x52D	BOOL	Unknown28
//	0x52E	BOOL	Unknown29
//	0x530	BYTE[8]	OptionalWarheads
//	0x538	BYTE[4]	OptionalBeams
//	0x53E	BYTE[3]	OptionalCountermeasures
//	0x542	BYTE	OptionalCraftCategory
//	0x543	BYTE[10] OptionalCraft
//	0x54D	BYTE[10] NumberOfOptionalCraft
//	0x557	BYTE[10] NumberOfOptionalCraftWaves
	}

	public function getLength(){
		return self::FLIGHT_GROUP_LENGTH;
	}

	public function __toString(){
		return count($this) . 'x ' . $this->ShipType->Abbr . ' ' . $this->Name .
			"";
	}

	public function count(){
		return (int)($this->waves+1) * (int)$this->perWave;
	}

	public function isInDifficultyLevel($level){
		if (!$this->arrives()) return FALSE;

		if ($this->difficulty === 'All') return TRUE;
		return strpos($this->difficulty, $level) !== FALSE;
	}

	private function arrives(){
		//TODO add conditions which might stop the FG arriving.
		//e.g. on 100% player destruction is the trigger
		return TRUE;
	}

	public function pointValue($level){
		$perShip = $this->ShipType->getPoints();
		//TODO add warhead points
		$total = count($this) * $perShip;

		if ($level == 'Easy') 		$total *= 0.5;
		if ($level == 'Hard') 		$total *=   2;

		if ($this->isFriendly()) 	$total *=  -1;

		return $total;
	}

	public function isFriendly(){
		return $this->team == 0;
	}

	public function isPlayerCraft(){
		return (bool)$this->playerNumber;
	}
}