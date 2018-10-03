<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class Message implements Byteable {
	use HexDecoder;

	const MESSAGE_LENGTH = 0xA2;

	public $NessageIndex;
	public $Message;
	public $SetToTeam;
	public $Trigger1;
	public $Trigger2;
	public $Unknown1;
	public $Trigger1OrTrigger2;
	public $Trigger3;
	public $Trigger4;
	public $Trigger3OrTrigger4;
	public $Voice;
	public $OriginatingFG;
	public $DelaySeconds;
	public $DelayMinutes;
	public $Color;
	public $Triggers12OrTriggers34;
	public $Cancel1;
	public $Cancel2;
	public $Cancel1OrCancel2;
	public $Unknown2;
	public function __construct($hex){
		$this->NessageIndex = $this->getShort($hex, 0x00);
		$this->Message = $this->getString($hex, 0x02, 64);
		$this->SetToTeam = array();
		for ($i = 0; $i < 10; $i++){
			$this->SetToTeam[] = $this->getByte($hex, 0x52 + $i);
		}

		$this->Trigger1 = new Trigger(substr($hex, 0x5C));
		$this->Trigger2 = new Trigger(substr($hex, 0x62));
		$this->Unknown1 = $this->getByte($hex, 0x68);
		$this->Trigger1OrTrigger2 = $this->getBool($hex, 0x6A);
		$this->Trigger3 = new Trigger(substr($hex, 0x6C));
		$this->Trigger4 = new Trigger(substr($hex, 0x72));
		$this->Trigger3OrTrigger4 = $this->getBool($hex, 0x7A);
		$this->Voice = $this->getString($hex, 0x7C, 8);
		$this->OriginatingFG = $this->getByte($hex, 0x84);
		$this->DelaySeconds = $this->getByte($hex, 0x8C);
		$this->DelayMinutes = $this->getByte($hex, 0x8D);
		$this->Color = $this->getByte($hex, 0x8E);
		$this->Triggers12OrTriggers34 = $this->getBool($hex, 0x8F);
		$this->Cancel1 = new Trigger(substr($hex, 0x90));
		$this->Cancel2 = new Trigger(substr($hex, 0x96));
		$this->Cancel1OrCancel2 = $this->getBool($hex, 0x9E);
		$this->Unknown2 = $this->getBool($hex, 0xA0);
	}

    public function getLength(){
        return self::MESSAGE_LENGTH;
    }
}