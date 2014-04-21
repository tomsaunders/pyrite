<?php

namespace Pyrite\TIE;

class ShipType {
    public $ID;
    public $Name;

    public function __construct($ID){
        $this->ID = ord($ID);
        $this->Name = $this->getName();
    }

    public function isStarship(){
        switch ($this->Name){
            //TODO decide on the canonical source of names
            case 'Corellian Corvette':
            case 'Modified Corvette':
            case 'Nebulon B Frigate':
            case 'Modified Frigate':
            case 'C-3 Passenger Liner':
            case 'Carrack Cruiser':
            case 'Strike Cruiser':
            case 'Escort Carrier':
            case 'Dreadnaught':
            case 'Calamari Cruiser':
            case 'Lt Calamari Cruiser':
            case 'Interdictor Cruiser':
            case 'Victory-class Star Destroyer':
            case 'Star Destroyer':
            case 'Super Star Destroyer':
                return TRUE;
            default:
                return FALSE;
        }

    }

    private function getName(){
        $names = array(
            'Unassigned', 'X-Wing', 'Y-Wing', 'A-Wing', 'B-Wing', 'TIE Fighter', 'TIE Interceptor', 'TIE Bomber', 'TIE Advanced', 'TIE Defender', 'Slot 10',
            'Slot 11', 'Missile Boat', 'T-Wing', 'Z-95 Headhunter', 'R-41 Starchaser', 'Assault Gunboat', 'Shuttle', 'Escort Shuttle', 'System Patrol Craft', 'Scout Craft',
            'Transport', 'Assault Transport', 'Escort Transport', 'Tug', 'Combat Utility Vehicle', 'Container A', 'Container B', 'Container C', 'Container D',
            'Heavy Lifter', 'Bulk Barge', 'Freighter', 'Cargo Ferry', 'Modular Conveyor', 'Container Transport', 'Medium Transport', 'Muurian Transport', 'Corellian Transport', 'Millenium Falcon', 'Corellian Corvette',
            'Modified Corvette', 'Nebulon B Frigate', 'Modified Frigate', 'C3 Passenger Liner', 'Carrack Cruiser', 'Strike Cruiser', 'Escort Carrier', 'Dreadnaught', 'Calamari Cruiser', 'Lt Calamari Cruiser',
            'Interdictor Cruiser', 'Victory Star Destroyer', 'Star Destroyer', 'Super Star Destroyer', 'Container E', 'Container F', 'Container G',
            'Container H', 'Container I', 'Platform A', 'Platform B', 'Platform C', 'Platform D', 'Platform E', 'Platform F', 'Asteroid Hanger', 'Asteroid Laser Platform', 'Asteroid Warhead Platform',
            'X7 Factory', 'Comm Satellite A', 'Comm Satellite B', 'Comm Satellite C', 'Comm Satellite D', 'Comm Satellite E', 'Class 1 Mine', 'Class 2 Mine', 'Class 3 Mine', 'Class 4 Mine', 'Gun Emplacement',
            'Probe A', 'Probe B', 'Probe C', 'Nav Buoy A', 'Nav Buoy B', 'Pilot', 'Asteroid', 'Planet');
        return $names[$this->ID];
    }
} 