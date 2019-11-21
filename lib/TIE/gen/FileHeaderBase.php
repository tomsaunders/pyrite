<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class FileHeaderBase extends PyriteBase implements Byteable {
	use HexDecoder;

	const FILEHEADER_LENGTH = 0x1CA;

	/** @var SHORT */
	public $PlatformID; // (-1)
	/** @var SHORT */
	public $NumFGs;
	/** @var SHORT */
	public $NumMessages;
	/** @var SHORT */
	public $Reserved; // (3) might be # of GlobalGoals
	/** @var BYTE */
	public $Unknown1;
	/** @var BOOL */
	public $Unknown2;
	/** @var BYTE */
	public $BriefingOfficers;
	/** @var BOOL */
	public $CapturedOnEject;
	/** @var CHAR<64> */
	public $EndOfMissionMessages;
	/** @var CHAR<12> */
	public $OtherIffNames;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->PlatformID = $this->getShort($hex, 0x000);
		$this->NumFGs = $this->getShort($hex, 0x002);
		$this->NumMessages = $this->getShort($hex, 0x004);
		$this->Reserved = $this->getShort($hex, 0x006);
		$this->Unknown1 = $this->getByte($hex, 0x008);
		$this->Unknown2 = $this->getBool($hex, 0x009);
		$this->BriefingOfficers = $this->getByte($hex, 0x00A);
		$this->CapturedOnEject = $this->getBool($hex, 0x00D);

        $this->EndOfMissionMessages = [];
        $offset = 0x018;
        for ($i = 0; $i < 6; $i++) {
            $t = $this->getChar($hex, $offset, 64);
            $this->EndOfMissionMessages[] = $t;
            $offset += 64;
        }

        $this->OtherIffNames = [];
        $offset = 0x19A;
        for ($i = 0; $i < 4; $i++) {
            $t = $this->getChar($hex, $offset, 12);
            $this->OtherIffNames[] = $t;
            $offset += 12;
        }
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"PlatformID" => $this->PlatformID,
			"NumFGs" => $this->NumFGs,
			"NumMessages" => $this->NumMessages,
			"Reserved" => $this->Reserved,
			"Unknown1" => $this->Unknown1,
			"Unknown2" => $this->Unknown2,
			"BriefingOfficers" => $this->getBriefingOfficersLabel(),
			"CapturedOnEject" => $this->CapturedOnEject,
			"EndOfMissionMessages" => $this->EndOfMissionMessages,
			"OtherIffNames" => $this->OtherIffNames		];
	}

        protected function getBriefingOfficersLabel() {
            return isset($this->BriefingOfficers) && isset(Constants::$BRIEFINGOFFICERS[$this->BriefingOfficers]) ? Constants::$BRIEFINGOFFICERS[$this->BriefingOfficers] : "Unknown";
        }

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeShort($hex, $this->PlatformID, 0x000);
		$this->writeShort($hex, $this->NumFGs, 0x002);
		$this->writeShort($hex, $this->NumMessages, 0x004);
		$this->writeShort($hex, $this->Reserved, 0x006);
		$this->writeByte($hex, $this->Unknown1, 0x008);
		$this->writeBool($hex, $this->Unknown2, 0x009);
		$this->writeByte($hex, $this->BriefingOfficers, 0x00A);
		$this->writeBool($hex, $this->CapturedOnEject, 0x00D);

        $offset = 0x018;
        for ($i = 0; $i < 6; $i++) {
            $t = $this->EndOfMissionMessages[$i];
            $this->writeChar($hex, $this->EndOfMissionMessages[$i], $offset, 64);
            $offset += 64;
        }

        $offset = 0x19A;
        for ($i = 0; $i < 4; $i++) {
            $t = $this->OtherIffNames[$i];
            $this->writeChar($hex, $this->OtherIffNames[$i], $offset, 12);
            $offset += 12;
        }
		return $hex;
	}


    public function getLength(){
        return self::FILEHEADER_LENGTH;
    }
}