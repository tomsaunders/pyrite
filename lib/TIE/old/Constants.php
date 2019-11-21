<?php
namespace Pyrite\TIE;

class ConstantsOld
{
    public static $TRIGGER_CONDITIONS = array(
        'Always',
        'Created',
        'Destroyed',
        'Attacked',
        'Captured',
        'Identified',
        'Boarded',
        'Docked',
        'Disabled',
        'Survived',
        'None',
        'Unknown',
        'Completed Mission',
        'Completed Primary Goals',
        'Failed Primary Goals',
        'Completed Secondary Goals',
        'Failed Secondary Goals',
        'Completed Bonus Goals',
        'Failed Bonus Goals',
        'Dropped Off',
        'Reinforced',
        '0% Shields',
        '50% Hull',
        'Out of warheads',
        'Unknown'
    );

    public static $TRIGGER_VALUE_TYPE = array(
        'Default',
        'Flight Group',
        'Ship Type',
        'Ship Category',
        'Object Category',
        'IFF',
        'Assignment',
        'Craft when',
        'Global Group',
        'General'
    );

    public static $TRIGGER_AMOUNT = array(
        '100%',
        '75%',
        '50%',
        '25%',
        'At least one',
        'All but one',
        'Special craft',
        'Non special craft',
        'Non-player craft',
        'Player craft'
    );

    public static $FG_AI = array('Rookie', 'Novice', 'Veteran', 'Officer', 'Ace', 'Top Ace');

    public static $FG_COLOUR = array('None', 'Red', 'Gold', 'Blue');

    public static $FG_STATUS = array(
        'Normal',
        '2x missiles',
        'half missiles',
        'Shields down',
        'Half shields',
        'No lasers',
        'No hyperdrive',
        'Shields 0%, charging',
        'Shields added',
        'Hyperdrive added',
        20 => 'Invincible'
    );

    public static $FG_WARHEAD = array(
        'None',
        'Heavy bombs',
        'Heavy rockets',
        'Missiles',
        'Torpedoes',
        'Adv missiles',
        'Adv torpedoes',
        'Mag pulse'
    );

    public static $FG_FORMATION = array(
        'Victory',
        'Finger four',
        'Line astern',
        'Line abreast',
        'Echelon right',
        'Echelon left',
        'Double astern left',
        'Diamond',
        'Stacked high',
        'High X',
        'Victory abreast',
        'Line astern high victory',
        'Reverse high victory',
        'Stacked low',
        'High S',
        'Tactical',
        'Echelon left high',
        'Echelon left high reverse',
        'Line abreast high',
        'Line astern low',
        'High victory',
        'Line abreast low victory',
        'double astern right',
        'line astern stacked low',
        'line abreast stacked low',
        'diamond II',
        'Diamond high',
        'flat pentagon',
        'side pentagon',
        'front pentagon',
        'flat hexagon',
        'side hexagon',
        'front hexagon',
        'single point'
    );

    public static $FG_BEAM = array('None', 'Tractor', 'Jamming', 'Decoy');

    public static $FG_ABORT = array('None', 'Shields down', 'Missiles out', 'Hull at 50%', 'Attacked');

    public static $FG_GOALAMOUNT = array('100%', '50%', 'At least one', 'All but one', 'The special craft');

    public static $FG_ORDERS = array(
        'Hold Steady',
        'Go Home',
        'Circle',
        'Circle and Evade',
        'Rendezvous',
        'Disabled',
        'Awaiting Boarding',
        'Attack Targets',
        'Disable Target',
        'Attack Escorts',
        'Protect',
        'Escort',
        'Disable Targets',
        'Board to Give',
        'Board to Take',
        'Board to exchange',
        'Board to Capture',
        'Board to Destroy',
        'Pick Up Craft',
        'Drop Off Craft',
        'Wait',
        'SS Wait',
        'SS Patrol loop',
        'SS Await return',
        'SS Launch',
        'SS Patrol and Protect',
        'SS Wait/Protect',
        'SS Patrol and Attack',
        'SS Patrol and Disable',
        'SS Hold Station',
        'SS Go Home',
        'SS Wait',
        'SS Board',
        'Board to Repair',
        'Hold Station',
        'Hold Steady',
        'Go Home',
        'Evade Waypoint 1',
        'Rendezvous 2',
        'SS Disabled'
    );

    public static $FG_DIFFICULTY = array('All', 'Easy', 'Medium', 'Hard', 'Medium and Hard', 'Easy and Medium');

    public static $SHIP_TYPE = array(
        'Unassigned',
        'X-Wing',
        'Y-Wing',
        'A-Wing',
        'B-Wing',
        'TIE Fighter',
        'TIE Interceptor',
        'TIE Bomber',
        'TIE Advanced',
        'TIE Defender',
        'Slot 10',
        'Slot 11',
        'Missile Boat',
        'T-Wing',
        'Z-95 Headhunter',
        'R-41 Starchaser',
        'Assault Gunboat',
        'Shuttle',
        'Escort Shuttle',
        'System Patrol Craft',
        'Scout Craft',
        'Transport',
        'Assault Transport',
        'Escort Transport',
        'Tug',
        'Combat Utility Vehicle',
        'Container A',
        'Container B',
        'Container C',
        'Container D',
        'Heavy Lifter',
        'Bulk Barge',
        'Freighter',
        'Cargo Ferry',
        'Modular Conveyor',
        'Container Transport',
        'Medium Transport',
        'Muurian Transport',
        'Corellian Transport',
        'Millenium Falcon',
        'Corellian Corvette',
        'Modified Corvette',
        'Nebulon B Frigate',
        'Modified Frigate',
        'C3 Passenger Liner',
        'Carrack Cruiser',
        'Strike Cruiser',
        'Escort Carrier',
        'Dreadnaught',
        'Calamari Cruiser',
        'Lt Calamari Cruiser',
        'Interdictor Cruiser',
        'Victory Star Destroyer',
        'Star Destroyer',
        'Super Star Destroyer',
        'Container E',
        'Container F',
        'Container G',
        'Container H',
        'Container I',
        'Platform A',
        'Platform B',
        'Platform C',
        'Platform D',
        'Platform E',
        'Platform F',
        'Asteroid Hanger',
        'Asteroid Laser Platform',
        'Asteroid Warhead Platform',
        'X7 Factory',
        'Comm Satellite A',
        'Comm Satellite B',
        'Comm Satellite C',
        'Comm Satellite D',
        'Comm Satellite E',
        'Class 1 Mine',
        'Class 2 Mine',
        'Class 3 Mine',
        'Class 4 Mine',
        'Gun Emplacement',
        'Probe A',
        'Probe B',
        'Probe C',
        'Nav Buoy A',
        'Nav Buoy B',
        'Pilot',
        'Asteroid',
        'Planet'
    );

    public static $SHIP_CATEGORY = array(
        'Starfighters',
        'Transports',
        'Freighters',
        'Capital ships',
        'Utility craft',
        'Platforms',
        'Mines'
    );

    public static $OBJECT_CATEGORY = array('Spacecraft', 'Weapons', 'Satellites');

    public static $TRIGGER_GENERAL = array(
        'Rookie Pilot',
        'Novice Pilot',
        'Veteran Pilot',
        'officer Pilot',
        'Ace Pilot',
        'Top Ace Pilot',
        'Stationary',
        'Flying Home',
        'Non-evading craft',
        'In formation',
        'En Rendezvous',
        'Disabled',
        'Awaiting boarding',
        'On patrol',
        'Attacking escorts',
        'Protecting craft',
        'Escorting craft',
        'Disabling craft',
        'Delivering craft',
        'Seizing craft',
        'Exchanging craft',
        'Capturing craft',
        'Craft destroying cargo',
        'Picking up craft',
        'Dropping off craft',
        'Waiting fighters',
        'Waiting starships',
        'Patrolling SS',
        'SS waiting for returns',
        'SS Waiting to launch',
        'SS waiting for boarding craft',
        'SS waiting for boarding craft',
        'SS attacking',
        'SS disabling',
        'SS disabling?',
        'SS flying home',
        'Rebels',
        'Imperials',
        '',
        'Spacecraft',
        'Weapons',
        'Satellites/Mines',
        '',
        '',
        '',
        '',
        '',
        'Fighters',
        'Transports',
        'Freighters',
        'Utility craft',
        'Starships',
        'Platforms',
        '',
        '',
        'Mines'
    );

    public static $TRIGGER_CRAFT_WHEN = array(
        '',
        'Boarding',
        'Boarded',
        'Defending',
        'Disabled',
        '',
        '',
        'Special craft',
        'Non-special craft',
        "Player's craft",
        'Non-player craft',
        ''
    );
}