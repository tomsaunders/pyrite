<?php

namespace Pyrite\XWING;

class ShipType {
    public $ID;
    public $Name;

    public function __construct($ID){
        $this->ID = ord($ID);
        $this->Name = lookup(Constants::$FG_SHIPTYPES, $ID);
    }

    public function isStarship(){
        return in_array($this->Name, Constants::$FG_STARSHIPS) !== FALSE;
    }

    public function pointValue(){
        return isset(Constants::$FG_POINTS[$this->ID]) ? (int)Constants::$FG_POINTS[$this->ID] : 0;
    }

	public function __toString(){
		return $this->Name;
	}
}