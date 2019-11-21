<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class BriefingBase extends PyriteBase implements Byteable {
	use HexDecoder;

	public $BriefingLength = 0;

	/** @var SHORT */
	public $RunningTime;
	/** @var SHORT */
	public $Unknown;
	/** @var SHORT */
	public $StartLength;
	/** @var INT */
	public $EventsLength; // Number of shorts used for events.
	/** @var Event */
	public $Events; // Set to 0 and impossible to generate in the same way, needs custom implementation
	/** @var Tag */
	public $Tags;
	/** @var TIEString */
	public $Strings;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->RunningTime = $this->getShort($hex, 0x000);
		$this->Unknown = $this->getShort($hex, 0x002);
		$this->StartLength = $this->getShort($hex, 0x004);
		$this->EventsLength = $this->getInt($hex, 0x006);

        $this->Events = [];
        $offset = 0x00A;
        for ($i = 0; $i < 0; $i++) {
            $t = new Event(substr($hex, $offset));;
            $this->Events[] = $t;
            $offset += $t->getLength();
        }

        $this->Tags = [];
        $offset = 0x32A;
        for ($i = 0; $i < 32; $i++) {
            $t = new Tag(substr($hex, $offset));;
            $this->Tags[] = $t;
            $offset += $t->getLength();
        }

        $this->Strings = [];
        
        for ($i = 0; $i < 32; $i++) {
            $t = new TIEString(substr($hex, $offset));;
            $this->Strings[] = $t;
            $offset += $t->getLength();
        }
		$this->BriefingLength = $offset;
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"RunningTime" => $this->RunningTime,
			"Unknown" => $this->Unknown,
			"StartLength" => $this->StartLength,
			"EventsLength" => $this->EventsLength,
			"Events" => $this->Events,
			"Tags" => $this->Tags,
			"Strings" => $this->Strings		];
	}

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeShort($hex, $this->RunningTime, 0x000);
		$this->writeShort($hex, $this->Unknown, 0x002);
		$this->writeShort($hex, $this->StartLength, 0x004);
		$this->writeInt($hex, $this->EventsLength, 0x006);

        $offset = 0x00A;
        for ($i = 0; $i < 0; $i++) {
            $t = $this->Events[$i];
            $this->writeObject($hex, $this->Events[$i], $offset);
            $offset += $t->getLength();
        }

        $offset = 0x32A;
        for ($i = 0; $i < 32; $i++) {
            $t = $this->Tags[$i];
            $this->writeObject($hex, $this->Tags[$i], $offset);
            $offset += $t->getLength();
        }

        $offset = $offset;
        for ($i = 0; $i < 32; $i++) {
            $t = $this->Strings[$i];
            $this->writeObject($hex, $this->Strings[$i], $offset);
            $offset += $t->getLength();
        }
		return $hex;
	}


    public function getLength(){
        return $this->BriefingLength;
    }
}