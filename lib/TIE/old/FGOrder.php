<?php

namespace Pyrite\TIE;

class FGOrder {
    public $order;
    public $Name;

    public function __construct($byteString){
		$ord = array();
		$ord['Order']       = lookup(Constants::$FG_ORDERS, $byteString[0]);
		$ord['Speed']       = getByte($byteString[1]);
		$ord['ParamA']      = getByte($byteString[2]);
		$ord['ParamB']      = getByte($byteString[3]);
		$ord['TargetTypeA'] = getByte($byteString[12]);
		$ord['TargetVal A'] = getByte($byteString[13]);
		$ord['TargetTypeB'] = getByte($byteString[14]);
		$ord['TargetVal B'] = getByte($byteString[15]);
		$ord['TargetA orB'] = getBool($byteString[16]) ? 'OR' : "AND";
		$ord['TargetTypeC'] = getByte($byteString[6]);
		$ord['TargetVal C'] = getByte($byteString[8]);
		$ord['TargetTypeD'] = getByte($byteString[7]);
		$ord['TargetVal D'] = getByte($byteString[9]);
		$ord['TargetC orD'] = getBool($byteString[10]) ? 'OR' : "AND";
		
        $this->order = $ord;
        $this->Name = $ord['Order'];
    }

    public function isStarship(){
        return (substr($this->Name, 0, 2) === 'SS');
    }

//    private function getName(){
//        $names = array(
//            'Hold Steady', 'Go Home', 'Circle', 'Circle and Evade', 'Rendezvous', 'Disabled', 'Awaiting Boarding', 'Attack Targets', 'Disable Target', 'Attack Escorts',
//            'Protect', 'Escort', 'Disable Targets', 'Board to Give', 'Board to Take', 'Board to exchange', 'Board to Capture', 'Board to Destroy', 'Pick Up Craft', 'Drop Off Craft',
//            'Wait', 'SS Wait', 'SS Patrol loop', 'SS Await return', 'SS Launch', 'SS Patrol and Protect', 'SS Wait/Protect', 'SS Patrol and Attack', 'SS Patrol and Disable',
//            'SS Hold Station', 'SS Go Home', 'SS Wait', 'SS Board', 'Board to Repair', 'Hold Station', 'Hold Steady', 'Go Home', 'Evade Waypoint 1', 'Rendezvous 2', 'SS Disabled');
//        return $names[$this->ID];
//    }
} 