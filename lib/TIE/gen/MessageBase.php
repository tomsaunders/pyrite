<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class MessageBase extends PyriteBase implements Byteable {
	use HexDecoder;

	const MESSAGE_LENGTH = 0x5A;

	/** @var STR<64> */
	public $Message;
	/** @var Trigger */
	public $Triggers;
	/** @var STR<12> */
	public $EditorNote;
	/** @var BYTE */
	public $DelaySeconds;
	/** @var BOOL */
	public $Trigger1OrTrigger2;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->Message = $this->getString($hex, 0x00, 64);

        $this->Triggers = [];
        $offset = 0x40;
        for ($i = 0; $i < 2; $i++) {
            $t = new Trigger(substr($hex, $offset));;
            $this->Triggers[] = $t;
            $offset += 0x4;
        }
		$this->EditorNote = $this->getString($hex, 0x48, 12);
		$this->DelaySeconds = $this->getByte($hex, 0x58);
		$this->Trigger1OrTrigger2 = $this->getBool($hex, 0x59);
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"Message" => $this->Message,
			"Triggers" => $this->Triggers,
			"EditorNote" => $this->EditorNote,
			"DelaySeconds" => $this->DelaySeconds,
			"Trigger1OrTrigger2" => $this->Trigger1OrTrigger2		];
	}

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeString($hex, $this->Message, 0x00, 64);

        $offset = 0x40;
        for ($i = 0; $i < 2; $i++) {
            $t = $this->Triggers[$i];
            $this->writeObject($hex, $this->Triggers[$i], $offset);
            $offset += 0x4;
        }
		$this->writeString($hex, $this->EditorNote, 0x48, 12);
		$this->writeByte($hex, $this->DelaySeconds, 0x58);
		$this->writeBool($hex, $this->Trigger1OrTrigger2, 0x59);
		return $hex;
	}


    public function getLength(){
        return self::MESSAGE_LENGTH;
    }
}