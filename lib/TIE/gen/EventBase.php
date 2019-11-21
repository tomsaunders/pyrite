<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

abstract class EventBase extends PyriteBase implements Byteable {
	use HexDecoder;

	public $EventLength = 0;

	/** @var SHORT */
	public $Time;
	/** @var SHORT */
	public $EventType;
	/** @var SHORT */
	public $Variables;

	public function __construct($hex){
		$this->hex = $hex;
		$offset = 0;
		$this->Time = $this->getShort($hex, 0x0);
		$this->EventType = $this->getShort($hex, 0x2);

        $this->Variables = [];
        $offset = 0x4;
        for ($i = 0; $i < $this->VariableCount(); $i++) {
            $t = $this->getShort($hex, $offset);
            $this->Variables[] = $t;
            $offset += 2;
        }
		$this->EventLength = $offset;
		$this->afterConstruct();
	}

	public function __debugInfo() {
		return [
			"Time" => $this->Time,
			"EventType" => $this->getEventTypeLabel(),
			"Variables" => $this->Variables		];
	}

        protected function getEventTypeLabel() {
            return isset($this->EventType) && isset(Constants::$EVENTTYPE[$this->EventType]) ? Constants::$EVENTTYPE[$this->EventType] : "Unknown";
        }

	abstract protected function VariableCount();

	protected function toHexString() {

		$hex = "";

		$offset = 0;
		$this->writeShort($hex, $this->Time, 0x0);
		$this->writeShort($hex, $this->EventType, 0x2);

        $offset = 0x4;
        for ($i = 0; $i < $this->VariableCount(); $i++) {
            $t = $this->Variables[$i];
            $this->writeShort($hex, $this->Variables[$i], $offset);
            $offset += 2;
        }
		return $hex;
	}


    public function getLength(){
        return $this->EventLength;
    }
}