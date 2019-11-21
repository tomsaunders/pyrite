<?php
namespace Pyrite\TIE;

use Pyrite\Hex;
use Pyrite\MissionFormat;

class MissionOld extends MissionFormat
{
    public $filename;
    public $globalGoalCount = 3;

    public $goalMessages;

    const IFF_OFFSET = 3;
    const IFF_LENGTH = 12;
    public $IFF;

    const FG_LENGTH = 292;

    const MESSAGE_LENGTH = 90;
    const GLOBAL_GOAL_LENGTH = 28;

    public $preQuestions = array();
    public $postQuestions = array();

    public $remaining;
    public $unknownSet = array();

    public $globalGroups = array();

    public $lookups = array(
        'briefingOfficer' => array('None', 'Both', 'Flight Officer', 'Secret Order'),
        'goalAmount' => array('100%', '50%', 'At least one', 'All but one', 'The special craft'),
        'goalCond' => array(
            'Always',
            'Arrive',
            'Destroyed',
            'Attacked',
            'Captured',
            'Identified',
            'Boarded',
            'Docking',
            'Disabled',
            'Withdraw',
            'None',
            'Unknown',
            'Mission completed',
            'Primary completed',
            'Primary failed',
            'Secondary completed',
            'Secondary failed',
            'Bonus completed',
            'Bonus failed',
            'Deployed',
            'Requested',
            'Capturing',
            'Hull 50%',
            'Out of missiles'
        ),
        'switch' => array(
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
        ),
        'shipType' => array(
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
        ),
        'shipCategory' => array(
            'Starfighters',
            'Transports',
            'Freighters',
            'Capital ships',
            'Utility craft',
            'Platforms',
            'Mines'
        ),
        'objectCategory' => array('Spacecraft', 'Weapons', 'Satellites'),
        'IFF' => array('Rebel', 'Imperial'),
        'orders' => array(
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
        ),
        'generalCond' => array(
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
        ),
        'craftWhen' => array(
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
        ),
        'percentage' => array(
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
        ),
        'AI' => array('Rookie', 'Novice', 'Veteran', 'Officer', 'Ace', 'Top Ace'),
        'colour' => array('None', 'Red', 'Gold', 'Blue'),
        'status' => array(
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
        ),
        'warheads' => array(
            'None',
            'Heavy bombs',
            'Heavy rockets',
            'Missiles',
            'Torpedoes',
            'Adv missiles',
            'Adv torpedoes',
            'Mag pulse'
        ),
        'beam' => array('None', 'Tractor', 'Jamming', 'Decoy'),
        'formation' => array(
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
        ),
        'difficulty' => array('All', 'Easy', 'Medium', 'Hard', 'Medium and Hard', 'Easy and Medium'),
        'abort' => array('None', 'Shields down', 'Missiles out', 'Hull at 50%', 'Attacked')
    );

    function __construct($source, $justHeader = false)
    {
        if (strlen($source) < 100) {
            if (!file_exists($source)) {
                $source .= '.tie';
            }
            $hex = file_get_contents($source);
            $this->filename = $source;
        } else {
            $hex = $source;
        }
        $remaining = $this->readHeader($hex);
        if ($this->header->valid && !$justHeader) {
            $remaining = $this->readGoalMessages($remaining);
            $remaining = $this->readIFF($remaining);
            $remaining = $this->readFlightGroups($remaining);
            $remaining = $this->readMessages($remaining);
            $remaining = $this->readGlobalGoals($remaining);
            $remaining = $this->readBriefing($remaining);
            $remaining = $this->readPreQuestions($remaining);
            $remaining = $this->readPostQuestions($remaining);
        }
        $this->remaining = $remaining;
    }

    function valid()
    {
        return $this->header->valid;
    }

    function readPreQuestions($hex)
    {
        $this->preQuestions = array('Officer' => array(), 'Secret' => array());
        foreach ($this->preQuestions as &$questions) {
            for ($i = 0; $i < 5; $i++) {
                list($length, $question) = $this->readPreQuestion($hex);
                $hex = substr($hex, $length + 2);
                if ($length) {
                    $questions[] = $question;
                }
            }
        }
        return $hex;
    }

    function readPreQuestion($hex)
    {
        $length = getShort($hex, 0);
        $question = '';
        $answer = '';
        if ($length !== 0) {
            $text = substr($hex, 2, $length);
            if (strpos($text, chr(10))) {
                list($question, $answer) = explode(chr(10), $text, 2);
            }
        }
        return array($length, array($question => $answer));
    }

    function readPostQuestion($hex)
    {
        $length = strlen($hex) ? getShort($hex, 0) : 0;
        $post = array();
        if ($length !== 0) {
            $post['Condition'] = getByte($hex, 2);
            $post['Type'] = getByte($hex, 3);
            $text = substr($hex, 4, $length);
            if (strpos($text, chr(10))) {
                list($question, $answer) = explode(chr(10), $text, 2);
                $post[$question] = $answer;
            }
        }
        return array($length, $post);
    }

    function readPostQuestions($hex)
    {
        $this->postQuestions = array('Officer' => array(), 'Secret' => array());
        foreach ($this->postQuestions as &$questions) {
            for ($i = 0; $i < 5; $i++) {
                list($length, $question) = $this->readPostQuestion($hex);
                $hex = substr($hex, $length + 2);
                if ($length) {
                    $questions[] = $question;
                }
            }
        }
        return $hex;
    }

    function readBriefingEvent($hex)
    {
        $event = new BriefingEvent($hex);
        return array($event->getLength(), $event);
    }

    function readBriefing($hex)
    {
        $brief = array();
        $brief['RunningTime'] = getShort($hex, 0);
        $brief['Unknown'] = getShort($hex, 2);
        $brief['StartLength'] = getShort($hex, 4);
        $brief['EventLength'] = getInt($hex, 6);

        //        $eventHex = substr($hex, 10, 800);
        ////        $brief['EventHex'] = Hex::hexToStr($eventHex);
        //        $brief['Events'] = array();
        //        for ($i = 0; $i < $brief['EventLength']; $i++){
        //            list($length, $event) = $this->readBriefingEvent($eventHex);
        //            $eventHex = substr($eventHex, $length);
        //            $brief['Events'][] = (string)$event;
        //        }

        $eventHex = substr($hex, 10, $brief['EventLength'] * 2);
        while (strlen($eventHex)) {
            list($length, $event) = $this->readBriefingEvent($eventHex);
            $eventHex = substr($eventHex, $length);
            $brief['Events'][] = (string) $event;
        }

        $hex = substr($hex, 810); //tags, strings
        $brief['Tags'] = array();
        for ($t = 0; $t < 32; $t++) {
            $tag = $this->readTag($hex);
            $length = (!empty($tag)) ? (strlen($tag) + 2) : 2;
            $hex = substr($hex, $length);
            if ($tag) {
                $brief['Tags'][] = $tag;
            }
        }

        $brief['Strings'] = array();
        for ($s = 0; $s < 32; $s++) {
            $tag = $this->readTag($hex);
            $length = (!empty($tag)) ? (strlen($tag) + 2) : 2;
            $hex = substr($hex, $length);
            if ($tag) {
                $brief['Strings'][] = $tag;
            }
        }

        $this->briefing = $brief;
        return $hex;
    }

    function readTag($hex)
    {
        $length = getShort($hex, 0);
        if ($length === 0) {
            return '';
        } else {
            return substr($hex, 2, $length); //length + length byte
        }
    }

    function readGlobalGoals($remaining)
    {
        $key = array('Primary', 'Secondary', 'Bonus');
        for ($i = 0; $i < $this->globalGoalCount; $i++) {
            $this->globalGoals[$key[$i]] = $this->readGlobalGoal(
                substr($remaining, self::GLOBAL_GOAL_LENGTH * $i, self::GLOBAL_GOAL_LENGTH)
            );
        }
        return substr($remaining, self::GLOBAL_GOAL_LENGTH * $this->globalGoalCount);
    }

    function readGlobalGoal($hex)
    {
        return array(
            'TriggerA' => $this->getTrigger($hex, 0),
            'TriggerB' => $this->getTrigger($hex, 4),
            'A or B' => getBool($hex, 25)
        );
    }

    function readMessages($remaining)
    {
        for ($i = 0; $i < $this->header->messageCount; $i++) {
            $this->messages[] = $this->readMessage(substr($remaining, self::MESSAGE_LENGTH * $i, self::MESSAGE_LENGTH));
        }
        return substr($remaining, self::MESSAGE_LENGTH * $this->header->messageCount);
    }

    function readMessage($hex)
    {
        return array(
            'message' => getString($hex, 0, 64),
            'color' => 'lookup.. char/1/2/3 = red/green/blue/purple',
            'trigA' => $this->getTrigger($hex, 64, 4),
            'trigB' => $this->getTrigger($hex, 68, 4),
            'ed note' => getString($hex, 72, 12),
            'delay' => getByte($hex, 88),
            'trig1 or 2' => getBool($hex, 89)
        );
    }

    function getTrigger($hex, $startPos = null)
    {
        if ($startPos !== null) {
            $hex = substr($hex, $startPos);
        }

        return new Trigger($hex, $this);
    }

    function readFlightGroups($remaining)
    {
        for ($i = 0; $i < $this->header->flightGroupCount; $i++) {
            $this->flightGroups[] = $this->readFlightGroup(substr($remaining, self::FG_LENGTH * $i, self::FG_LENGTH));
        }
        return substr($remaining, self::FG_LENGTH * $this->header->flightGroupCount);
    }

    function hasUnknowns($hex)
    {
        $unknownPos = array(
            11,
            17,
            22,
            23,
            24,
            35,
            42,
            43,
            45,
            46,
            47,
            60,
            61,
            67,
            73,
            78,
            79,
            85,
            96,
            97,
            102,
            103,
            108,
            109,
            119,
            240,
            241,
            242,
            243
        );
        $constants = array(66 => hexdec('01'), 72 => hexdec('01'), 84 => hexdec('01'));
        foreach ($unknownPos as $pos) {
            if (ord($hex[$pos]) > 0) {
                $this->unknownSet[$pos] = sprintf('%02X', ord($hex[$pos]));
            }
        }
        foreach ($constants as $pos => $value) {
            if (ord($hex[$pos]) !== $value) {
                $this->unknownSet[$pos] = sprintf('%02X', ord($hex[$pos]));
            }
        }
    }

    function readFlightGroup($hex)
    {
        $fg = new FlightGroup($hex, $this);
        if ($fg->globalGroup) {
            if (!isset($this->globalGroups[$fg->globalGroup])) {
                $this->globalGroups[$fg->globalGroup] = array();
            }
            $this->globalGroups[$fg->globalGroup][] = $fg;
        }
        return $fg;
    }

    public function getGG($id)
    {
        if (isset($this->globalGroups[$id])) {
            $fgs = array();
            foreach ($this->globalGroups[$id] as $fg) {
                if ($fg instanceof FlightGroup) {
                    $fgs[] = (string) $fg;
                }
            }
            return implode(", ", $fgs);
        } else {
            return "no gg";
        }
    }

    function lookup($key, $value)
    {
        $value = getByte($value);

        if (!isset($this->lookups[$key])) {
            return "Unknown lookup $key";
        }
        if (!isset($this->lookups[$key][$value])) {
            return "Unknown lookup $key / $value";
        }
        return $this->lookups[$key][$value];
    }

    function lookupShort($key, $hex, $startPos = null)
    {
        $value = getShort($hex, $startPos);

        if (!isset($this->lookups[$key])) {
            return "Unknown lookup $key";
        }
        if (!isset($this->lookups[$key][$value])) {
            return "Unknown lookup $key / $value";
        }
        return $this->lookups[$key][$value];
    }

    function lookupSwitch($switch, $value)
    {
        $value = getByte($value);
        return "Unknown lookup switch $value";
    }

    function getFG($idx)
    {
        return isset($this->flightGroups[$idx]) ? $this->flightGroups[$idx] : null;
    }

    function getFGString($idx)
    {
        $fg = $this->flightGroups[$idx];
        return (string) $fg;
    }

    function getFGsByIFF($iff)
    {
        $fgs = array();
        foreach ($this->flightGroups as $fg) {
            if ($iff == $fg->getIFF()) {
                $fgs[] = $fg;
            }
        }
        return $fgs;
    }

    function readIFF($remaining)
    {
        $remaining = substr($remaining, self::IFF_OFFSET);
        $this->IFF = new IFF($remaining);
        return substr($remaining, $this->IFF->getLength());
    }

    function readGoalMessages($hex)
    {
        $this->goalMessages = new GoalMessages($hex);
        return substr($hex, $this->goalMessages->getLength());
    }

    function readHeader($hex)
    {
        $this->header = new Header($hex);
        return substr($hex, $this->header->getLength());
    }

    function printDump()
    {
        return array(
            'filename' => $this->filename,
            'header' => (string) $this->header,
            'Goal messages' => (string) $this->goalMessages,
            //            'IFF' => (string)$this->IFF,
            //            'Flight Groups' => $this->flightGroups,
            //            'Messages' => $this->messages,
            'Global Goals' => $this->globalGoals
            //            'Briefing' => $this->briefing,
            //            'PreQuestions' => $this->preQuestions,
            //            'PostQuestions' => $this->postQuestions,
            //            'Remaining' => Hex::hexToStr($this->remaining)
        );
    }

    function hasReinforcements()
    {
        foreach ($this->flightGroups as $fg) {
            if ($fg->isReinforcement()) {
                return (string) $fg;
            }
        }
        return false;
    }
}
