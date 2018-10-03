<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class FileHeader implements Byteable {
	const IFF_COUNT = 4;
	const REGIONCOUNT = 4;
	use HexDecoder;

	const FILEHEADER_LENGTH = 0x23F0;

	public $PlatformID;
	public $NumFGs;
	public $NumMessages;
	public $Unknown1;
	public $Unknown2;
	public $IFFNames;
	public $RegionNames;
	public $CargoNames;
	public $GlobalGroupNames;
	public $Hangar;
	public $TimeLimitMinutes;
	public $EndMissionWhenComplete;
	public $BriefingOfficer;
	public $BriefingLogo;
	public $Unknown3;
	public $Unknown4;
	public $Unknown5;
	public function __construct($hex){
		$this->PlatformID = $this->getShort($hex, 0x0000);
		$this->NumFGs = $this->getShort($hex, 0x0002);
		$this->NumMessages = $this->getShort($hex, 0x0004);
		$this->Unknown1 = $this->getBool($hex, 0x0008);
		$this->Unknown2 = $this->getBool($hex, 0x000B);
		$this->IFFNames = array();
		for ($i = 0; $i < self::IFF_COUNT; $i++){
			$this->IFFNames[] = $this->getString($hex, 0x0014 + $i * 20, 20);
		}

		$this->RegionNames = array();
		for ($i = 0; $i < self::REGIONCOUNT; $i++){
			$this->RegionNames[] = $this->getString($hex, 0x0064 + $i * 132, 132);
		}

		$this->CargoNames = array();
		for ($i = 0; $i < 16; $i++){
			$this->CargoNames[] = new GlobalCargo(substr($hex, 0x0274 + $i));
		}

		$this->GlobalGroupNames = array();
		for ($i = 0; $i < 16; $i++){
			$this->GlobalGroupNames[] = $this->getString($hex, 0x0B34 + $i * 87, 87);
		}

		$this->Hangar = $this->getByte($hex, 0x23AC);
		$this->TimeLimitMinutes = $this->getByte($hex, 0x23AE);
		$this->EndMissionWhenComplete = $this->getBool($hex, 0x23AF);
		$this->BriefingOfficer = $this->getByte($hex, 0x23B0);
		$this->BriefingLogo = $this->getByte($hex, 0x23B1);
		$this->Unknown3 = $this->getByte($hex, 0x23B3);
		$this->Unknown4 = $this->getByte($hex, 0x23B4);
		$this->Unknown5 = $this->getByte($hex, 0x23B5);
	}

    public function getLength(){
        return self::FILEHEADER_LENGTH;
    }
}