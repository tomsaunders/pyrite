<?php

namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\IFlightGroup;

class FlightGroup implements Byteable, IFlightGroup, \Countable {
	use HexDecoder;

	const FLIGHTGROUP_LENGTH = 0xE3E;

	public $Name;
	public $EnableDesignation;
	public $EnableDesignation2;
	public $Designation;
	public $Designation2;
	public $Unknown1;
	public $GlobalCargoIndex;
	public $GlobalSpecialCargoIndex;
	public $Cargo;
	public $SpecialCargo;
	public $CraftRole;
	public $SpecialCargoCraft;
	public $RandomSpecialCargoCraft;
	public $CraftType;
	public $NumberOfCraft;
	public $Status1;
	public $Warhead;
	public $Beam;
	public $Iff;
	public $Team;
	public $GroupAI;
	public $Markings;
	public $Radio;
	public $Formation;
	public $FormationSpacing;
	public $GlobalGroup;
	public $LeaderSpacing;
	public $NumberOfWaves;
	public $Unknown3;
	public $PlayerNumber;
	public $ArriveOnlyIfHuman;
	public $PlayerCraft;
	public $Yaw;
	public $Pitch;
	public $Roll;
	public $Unknown4;
	public $ArrivalDifficulty;
	public $Unknown5;
	public $Arrival1;
	public $Arrival2;
	public $Arrival1OrArrival2;
	public $Unknown6;
	public $Arrival3;
	public $Arrival4;
	public $Arrival3OrArrival4;
	public $Arrivals12OrArrivals34;
	public $ArrivalDelayMinutes;
	public $ArrivalDelaySeconds;
	public $Departure1;
	public $Departure2;
	public $Departure1OrDeparture2;
	public $DepartureDelayMinutes;
	public $DepartureDelaySeconds;
	public $AbortTrigger;
	public $Unknown7;
	public $Unknown8;
	public $ArrivalMothership;
	public $ArriveViaMothership;
	public $AlternateArrivalMothership;
	public $AlternateArriveViaMothership;
	public $DepartureMothership;
	public $DepartViaMothership;
	public $AlternateDepartureMothership;
	public $AlternateDepartViaMothership;
	public $Orders;
	public $Skips;
	public $FGGoals;
	public $StartPoints;
	public $HyperPoint;
	public $StartPointRegions;
	public $HyperPointRegion;
	public $Unknown16;
	public $Unknown17;
	public $Unknown18;
	public $Unknown19;
	public $Unknown20;
	public $Unknown21;
	public $Unknown22;
	public $Unknown23;
	public $Unknown24;
	public $Unknown25;
	public $Unknown26;
	public $Unknown27;
	public $Unknown28;
	public $Unknown29;
	public $Unknown30;
	public $Unknown31;
	public $EnableGlobalUnit;
	public $Unknown32;
	public $Unknown33;
	public $Countermeasures;
	public $CraftExplosionTime;
	public $Status2;
	public $GlobalUnit;
	public $OptionalWarheads;
	public $OptionalBeams;
	public $OptionalCountermeasures;
	public $OptionalCraftCategory;
	public $OptionalCraft;
	public $NumberOfOptionalCraft;
	public $NumberofOptionalCraftWaves;
	public $PilotID;
	public $Backdrop;
	public $Unknown34;
	public $Unknown35;
	public $Unknown36;
	public $Unknown37;
	public $Unknown38;
	public $Unknown39;
	public $Unknown40;
	public $Unknown41;

	public function __construct($hex) {
		$this->Name                         = $this->getString($hex, 0x000, 20);
		$this->EnableDesignation            = $this->getBool($hex, 0x014);
		$this->EnableDesignation2           = $this->getBool($hex, 0x015);
		$this->Designation                  = $this->getByte($hex, 0x016);
		$this->Designation2                 = $this->getByte($hex, 0x017);
		$this->Unknown1                     = $this->getByte($hex, 0x018);
		$this->GlobalCargoIndex             = $this->getByte($hex, 0x019);
		$this->GlobalSpecialCargoIndex      = $this->getByte($hex, 0x01A);
		$this->Cargo                        = $this->getString($hex, 0x028, 20);
		$this->SpecialCargo                 = $this->getString($hex, 0x03C, 20);
		$this->CraftRole                    = $this->getString($hex, 0x050, 20);
		$this->SpecialCargoCraft            = $this->getByte($hex, 0x069);
		$this->RandomSpecialCargoCraft      = $this->getBool($hex, 0x06A);
		$this->CraftType                    = new CraftType($this->getByte($hex, 0x06B));
		$this->NumberOfCraft                = $this->getByte($hex, 0x06C);
		$this->Status1                      = $this->getByte($hex, 0x06D);
		$this->Warhead                      = $this->getByte($hex, 0x06E);
		$this->Beam                         = $this->getByte($hex, 0x06F);
		$this->Iff                          = $this->getByte($hex, 0x070);
		$this->Team                         = $this->getByte($hex, 0x071);
		$this->GroupAI                      = $this->getByte($hex, 0x072);
		$this->Markings                     = $this->getByte($hex, 0x073);
		$this->Radio                        = $this->getByte($hex, 0x074);
		$this->Formation                    = $this->getByte($hex, 0x076);
		$this->FormationSpacing             = $this->getByte($hex, 0x077);
		$this->GlobalGroup                  = $this->getByte($hex, 0x078);
		$this->LeaderSpacing                = $this->getByte($hex, 0x079);
		$this->NumberOfWaves                = $this->getByte($hex, 0x07A);
		$this->Unknown3                     = $this->getByte($hex, 0x07B);
		$this->PlayerNumber                 = $this->getByte($hex, 0x07D);
		$this->ArriveOnlyIfHuman            = $this->getBool($hex, 0x07E);
		$this->PlayerCraft                  = $this->getByte($hex, 0x07F);
		$this->Yaw                          = $this->getByte($hex, 0x080);
		$this->Pitch                        = $this->getByte($hex, 0x081);
		$this->Roll                         = $this->getByte($hex, 0x082);
		$this->Unknown4                     = $this->getByte($hex, 0x084);
		$this->ArrivalDifficulty            = $this->lookup(Constants::$ARRIVALDIFFICULTY, $hex, 0x086);
		$this->Unknown5                     = $this->getByte($hex, 0x087);
		$this->Arrival1                     = new Trigger(substr($hex, 0x088));
		$this->Arrival2                     = new Trigger(substr($hex, 0x08E));
		$this->Arrival1OrArrival2           = $this->getBool($hex, 0x096);
		$this->Unknown6                     = $this->getBool($hex, 0x097);
		$this->Arrival3                     = new Trigger(substr($hex, 0x098));
		$this->Arrival4                     = new Trigger(substr($hex, 0x09E));
		$this->Arrival3OrArrival4           = $this->getBool($hex, 0x0A6);
		$this->Arrivals12OrArrivals34       = $this->getBool($hex, 0x0A8);
		$this->ArrivalDelayMinutes          = $this->getByte($hex, 0x0AA);
		$this->ArrivalDelaySeconds          = $this->getByte($hex, 0x0AB);
		$this->Departure1                   = new Trigger(substr($hex, 0x0AC));
		$this->Departure2                   = new Trigger(substr($hex, 0x0B2));
		$this->Departure1OrDeparture2       = $this->getBool($hex, 0x0BA);
		$this->DepartureDelayMinutes        = $this->getByte($hex, 0x0BC);
		$this->DepartureDelaySeconds        = $this->getByte($hex, 0x0BD);
		$this->AbortTrigger                 = $this->getByte($hex, 0x0BE);
		$this->Unknown7                     = $this->getByte($hex, 0x0BF);
		$this->Unknown8                     = $this->getByte($hex, 0x0C0);
		$this->ArrivalMothership            = $this->getByte($hex, 0x0C2);
		$this->ArriveViaMothership          = $this->getBool($hex, 0x0C3);
		$this->AlternateArrivalMothership   = $this->getByte($hex, 0x0C4);
		$this->AlternateArriveViaMothership = $this->getBool($hex, 0x0C5);
		$this->DepartureMothership          = $this->getByte($hex, 0x0C6);
		$this->DepartViaMothership          = $this->getBool($hex, 0x0C7);
		$this->AlternateDepartureMothership = $this->getByte($hex, 0x0C8);
		$this->AlternateDepartViaMothership = $this->getBool($hex, 0x0C9);
		$this->Orders                       = [];
		for ($i = 0; $i < 16; $i++) {
			$this->Orders[] = new Order(substr($hex, 0x0CA + $i));
		}

		$this->Skips = [];
		for ($i = 0; $i < 16; $i++) {
			$this->Skips[] = new Skip(substr($hex, 0xA0A + $i));
		}

		$this->FGGoals = [];
		for ($i = 0; $i < 8; $i++) {
			$this->FGGoals[] = new GoalFG(substr($hex, 0xB0A + $i));
		}

		$this->StartPoints = [];
		for ($i = 0; $i < 3; $i++) {
			$this->StartPoints[] = new Waypt(substr($hex, 0xD8A + $i));
		}

		$this->HyperPoint        = new Waypt(substr($hex, 0xDA2));
		$this->StartPointRegions = [];
		for ($i = 0; $i < 3; $i++) {
			$this->StartPointRegions[] = $this->getByte($hex, 0xDAA + $i);
		}

		$this->HyperPointRegion   = $this->getByte($hex, 0xDAD);
		$this->Unknown16          = $this->getByte($hex, 0xDAE);
		$this->Unknown17          = $this->getByte($hex, 0xDAF);
		$this->Unknown18          = $this->getByte($hex, 0xDB0);
		$this->Unknown19          = $this->getByte($hex, 0xDB1);
		$this->Unknown20          = $this->getByte($hex, 0xDB2);
		$this->Unknown21          = $this->getByte($hex, 0xDB3);
		$this->Unknown22          = $this->getBool($hex, 0xDB4);
		$this->Unknown23          = $this->getByte($hex, 0xDB6);
		$this->Unknown24          = $this->getByte($hex, 0xDB7);
		$this->Unknown25          = $this->getByte($hex, 0xDB8);
		$this->Unknown26          = $this->getByte($hex, 0xDB9);
		$this->Unknown27          = $this->getByte($hex, 0xDBA);
		$this->Unknown28          = $this->getByte($hex, 0xDBB);
		$this->Unknown29          = $this->getBool($hex, 0xDBC);
		$this->Unknown30          = $this->getBool($hex, 0xDC0);
		$this->Unknown31          = $this->getBool($hex, 0xDC1);
		$this->EnableGlobalUnit   = $this->getBool($hex, 0xDC4);
		$this->Unknown32          = $this->getByte($hex, 0xDC5);
		$this->Unknown33          = $this->getByte($hex, 0xDC6);
		$this->Countermeasures    = $this->getByte($hex, 0xDC7);
		$this->CraftExplosionTime = $this->getByte($hex, 0xDC8);
		$this->Status2            = $this->getByte($hex, 0xDC9);
		$this->GlobalUnit         = $this->getByte($hex, 0xDCA);
		$this->OptionalWarheads   = [];
		for ($i = 0; $i < 8; $i++) {
			$this->OptionalWarheads[] = $this->getByte($hex, 0xDCC + $i);
		}

		$this->OptionalBeams = [];
		for ($i = 0; $i < 4; $i++) {
			$this->OptionalBeams[] = $this->getByte($hex, 0xDD4 + $i);
		}

		$this->OptionalCountermeasures = [];
		for ($i = 0; $i < 3; $i++) {
			$this->OptionalCountermeasures[] = $this->getByte($hex, 0xDDA + $i);
		}

		$this->OptionalCraftCategory = $this->getByte($hex, 0xDDE);
		$this->OptionalCraft         = [];
		for ($i = 0; $i < 10; $i++) {
			$this->OptionalCraft[] = $this->getByte($hex, 0xDDF + $i);
		}

		$this->NumberOfOptionalCraft = [];
		for ($i = 0; $i < 10; $i++) {
			$this->NumberOfOptionalCraft[] = $this->getByte($hex, 0xDE9 + $i);
		}

		$this->NumberofOptionalCraftWaves = [];
		for ($i = 0; $i < 10; $i++) {
			$this->NumberofOptionalCraftWaves[] = $this->getByte($hex, 0xDF3 + $i);
		}

		$this->PilotID   = $this->getString($hex, 0xDFD, 16);
		$this->Backdrop  = $this->getByte($hex, 0xE12);
		$this->Unknown34 = $this->getBool($hex, 0xE29);
		$this->Unknown35 = $this->getBool($hex, 0xE2B);
		$this->Unknown36 = $this->getBool($hex, 0xE2D);
		$this->Unknown37 = $this->getBool($hex, 0xE2F);
		$this->Unknown38 = $this->getBool($hex, 0xE31);
		$this->Unknown39 = $this->getBool($hex, 0xE33);
		$this->Unknown40 = $this->getBool($hex, 0xE35);
		$this->Unknown41 = $this->getBool($hex, 0xE37);
	}

	public function getLength() {
		return self::FLIGHTGROUP_LENGTH;
	}

	public function isPlayerCraft() {
		return $this->PlayerNumber;
	}

	/**
	 * @param $level string difficulty
	 * @return bool whether this flight group is present in the provided difficulty level
	 */
	public function isInDifficultyLevel($level) {
		// TODO: Implement isInDifficultyLevel() method.
		return strpos($this->ArrivalDifficulty, $level) !== FALSE;
	}

	/**
	 * Get potential maximum point value for this flight group
	 * takes IFF into account, as well as difficulty level
	 * @param $level string difficulty
	 * @return int
	 */
	public function pointValue($level) {
		// TODO: Implement pointValue() method.
		return 1;
	}

	public function count(){
		return (int)$this->NumberOfCraft * ((int)$this->NumberOfWaves+1);
	}

	public function __toString() {
		return count($this) . 'x ' . $this->CraftType->Abbr . ' ' . $this->Name .
			" " . Constants::$GROUPAI[$this->GroupAI] . " " .
			"[{$this->ArrivalDifficulty}]" .
			($this->Warhead != "None" ? ' (' . $this->Warhead .')' : "") .
			($this->GlobalGroup ? ' (GG ' . $this->GlobalGroup .')' : "") .
			($this->GlobalUnit ? ' (GU ' . $this->GlobalUnit .')' : "");
	}

	public function describeArrival(){
		return [
			$this->ArrivalDifficulty,
			$this->Arrival1,
			$this->Arrival2,
		];
	}
}