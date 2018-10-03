<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class GoalGlobal implements Byteable {
	use HexDecoder;

	const GOALGLOBAL_LENGTH = 0x7A;

	public $Trigger1;
	public $Trigger2;
	public $Trigger1OrTrigger2;
	public $Unknown1;
	public $Trigger3;
	public $Trigger4;
	public $Trigger3OrTrigger4;
	public $Unknown2;
	public $Triggers12OrTriggers34;
	public $Unknown3;
	public $Points;
	public $Unknown4;
	public $Unknown5;
	public $Unknown6;
	public $ActiveSquence;
	public function __construct($hex){
		$this->Trigger1 = new Trigger(substr($hex, 0x0000));
		$this->Trigger2 = new Trigger(substr($hex, 0x0006));
		$this->Trigger1OrTrigger2 = $this->getBool($hex, 0x000E);
		$this->Unknown1 = $this->getBool($hex, 0x000F);
		$this->Trigger3 = new Trigger(substr($hex, 0x0010));
		$this->Trigger4 = new Trigger(substr($hex, 0x0016));
		$this->Trigger3OrTrigger4 = $this->getBool($hex, 0x001E);
		$this->Unknown2 = $this->getBool($hex, 0x0027);
		$this->Triggers12OrTriggers34 = $this->getBool($hex, 0x0031);
		$this->Unknown3 = $this->getByte($hex, 0x0032);
		$this->Points = $this->getSByte($hex, 0x0033);
		$this->Unknown4 = $this->getByte($hex, 0x0034);
		$this->Unknown5 = $this->getByte($hex, 0x0035);
		$this->Unknown6 = $this->getByte($hex, 0x0036);
		$this->ActiveSquence = $this->getByte($hex, 0x0038);
	}

    public function getLength(){
        return self::GOALGLOBAL_LENGTH;
    }
}