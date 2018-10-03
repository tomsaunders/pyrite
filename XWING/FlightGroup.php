<?php

namespace Pyrite\XWING;

use Pyrite\Byteable;
use Pyrite\Scoring;

class FlightGroup implements Byteable, \Countable, Scoring{
    public $general = array();
//    public $goals = array();
//    public $arrival = array();
//    public $orders = array();
//    public $navigation = array();

    public $objective;

    const FLIGHT_GROUP_LENGTH = 292;
	const FG_NAME_LENGTH = 16;

    public function __construct($byteString, Mission $TIE){
		$this->general = array(
			'Name'          => getString($byteString, self::FG_NAME_LENGTH * 0, self::FG_NAME_LENGTH),
			'Cargo'         => getString($byteString, self::FG_NAME_LENGTH * 1, self::FG_NAME_LENGTH),
			'Special Cargo' => getString($byteString, self::FG_NAME_LENGTH * 2, self::FG_NAME_LENGTH),
		);
		$byteString = substr($byteString, self::FG_NAME_LENGTH * 3);
		
		$this->general['SpecialShip']   = getShort($byteString);
		$this->general['ShipType']      = new ShipType($byteString[2]);
        $this->general['IFF']           = getShort($byteString, 4);
        $this->general['Ships']         = getShort($byteString, 8);
        $this->general['Waves']         = getShort($byteString, 10);
        $this->general['Player']        = getShort($byteString, 82);

        $this->objective                = lookup(Constants::$FG_OBJECTIVES, $byteString[94]);

    }

    public function getLength(){
        return self::FLIGHT_GROUP_LENGTH;
    }

    public function __toString(){
        return count($this) . 'x ' . $this->general['ShipType']->Name . ' ' . $this->general['Name']  ;
    }

    public function count(){
        return (int)$this->general['Ships'] * ((int)$this->general['Waves']+1);
    }

    public function pointValue($difficultyIsIrrelevant = NULL){
        $pts = count($this) * $this->general['ShipType']->pointValue();
        return $this->isFriendly() ? -5000 : $pts;
    }

    public function isFriendly(){
        return $this->general['IFF'] == 1;
    }

    /** @return bool whether the object is able to be destroyed - whether invincible or mission critical */
    public function destroyable()
    {
        return TRUE;
    }

    /** @return bool whether the object is invincible */
    public function invincible()
    {
        return FALSE;
    }

    /** @return bool whether the object is the player craft */
    public function isPlayerCraft()
    {
        return $this->general['Player'] != 0;
    }

    /** @return int the maximum number of warheads the craft may carry */
    public function maxWarheads()
    {
        return 6; // XW torps 6 AW miss 12 YW torp 8
    }
}