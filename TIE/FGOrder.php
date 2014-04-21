<?php

namespace Pyrite\TIE;

class FGOrder {
    public $order;
    public $Name;

    public function __construct($orderArray){
        $this->order = $orderArray;
        $this->Name = $orderArray['Order'];
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