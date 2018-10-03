<?php
namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class GlobalGoal implements Byteable {
	use HexDecoder;

	const GLOBALGOAL_LENGTH = 0x170;

	public $Reserved;
	public $Goals;
	public function __construct($hex){
		$this->Reserved = $this->getShort($hex, 0x00);
		$this->Goals = array();
		for ($i = 0; $i < 3; $i++){
			$this->Goals[] = new GoalGlobal(substr($hex, 0x02 + $i));
		}

	}

    public function getLength(){
        return self::GLOBALGOAL_LENGTH;
    }
}