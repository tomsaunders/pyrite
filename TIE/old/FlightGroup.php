<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;

class FlightGroupOld implements Byteable, \Countable
{
    public $general = array();
    public $globalGroup;
    public $goals = array();
    public $arrival = array();
    public $departure = array();
    public $orders = array();
    public $navigation = array();
    private $destroyable = true;
    private $captureable = false;

    const FLIGHT_GROUP_LENGTH = 292;
    const FG_NAME_LENGTH = 12;

    const BONUS_MULTIPLIER = 50;

    public function __construct($byteString, Mission $TIE)
    {
        $this->general = array(
            'Name' => getString($byteString, self::FG_NAME_LENGTH * 0, self::FG_NAME_LENGTH),
            'Pilot' => getString($byteString, self::FG_NAME_LENGTH * 1, self::FG_NAME_LENGTH),
            'Cargo' => getString($byteString, self::FG_NAME_LENGTH * 2, self::FG_NAME_LENGTH),
            'Special Cargo' => getString($byteString, self::FG_NAME_LENGTH * 3, self::FG_NAME_LENGTH)
        );
        $byteString = substr($byteString, self::FG_NAME_LENGTH * 4);

        $this->general['SpecialPos'] = getByte($byteString[0]);
        $this->general['SpecialRand'] = getBool($byteString[1]);
        $this->general['ShipType'] = new ShipType($byteString[2]);
        $this->general['Ships'] = getByte($byteString[3]);
        $this->general['Status'] = lookup(Constants::$FG_STATUS, $byteString[4]);
        $this->general['Warheads'] = lookup(Constants::$FG_WARHEAD, $byteString[5]);
        $this->general['Beam'] = lookup(Constants::$FG_BEAM, $byteString[6]);
        $this->general['IFF'] = $TIE->IFF->getIndex(getByte($byteString[7]));
        $this->general['AI'] = lookup(Constants::$FG_AI, $byteString[8]);
        $this->general['Colour'] = lookup(Constants::$FG_COLOUR, $byteString[9]);
        $this->general['ObeysRadio'] = getByte($byteString[10]);
        $this->general['Formation'] = lookup(Constants::$FG_FORMATION, $byteString[12]);
        $this->general['FormSpacing'] = getByte($byteString[13]); //NOT IN TFW
        $this->general['GlobalGroup'] = getByte($byteString[14]); //NOT IN TFW
        $this->general['LeaderSpacing'] = getByte($byteString[15]); //NOT IN TFW
        $this->general['Waves'] = getByte($byteString[16]);
        $this->general['PlayerCraft'] = getByte($byteString[18]);
        $this->general['OrientationX'] = getByte($byteString[19]); //NOT IN TFW
        $this->general['OrientationY'] = getByte($byteString[20]); //NOT IN TFW
        $this->general['OrientationZ'] = getByte($byteString[21]); //NOT IN TFW

        $this->globalGroup = $this->general['GlobalGroup'];

        $this->goals['PrimaryWhat'] = lookup(Constants::$TRIGGER_CONDITIONS, $byteString[110]);
        $this->goals['PrimaryWho'] = lookup(Constants::$FG_GOALAMOUNT, $byteString[111]);
        $this->goals['SecondaryWhat'] = lookup(Constants::$TRIGGER_CONDITIONS, $byteString[112]);
        $this->goals['SecondaryWho'] = lookup(Constants::$FG_GOALAMOUNT, $byteString[113]);
        $this->goals['BonusWhat'] = lookup(Constants::$TRIGGER_CONDITIONS, $byteString[116]);
        $this->goals['BonusWho'] = lookup(Constants::$FG_GOALAMOUNT, $byteString[117]);
        $this->goals['BonusPts'] = getSByte($byteString[118]) * self::BONUS_MULTIPLIER;

        $this->arrival['Difficulty'] = lookup(Constants::$FG_DIFFICULTY, $byteString[25]);
        error_log('Trigger A for ' . $this->__toString());
        $this->arrival['TriggerA'] = new Trigger(substr($byteString, 26), $TIE);
        error_log('Trigger B for ' . $this->__toString());
        $this->arrival['TriggerB'] = new Trigger(substr($byteString, 30), $TIE);
        $this->arrival['CondAB'] = getBool($byteString[34]) ? "OR" : "AND";
        $this->arrival['DelayMin'] = getByte($byteString[36]);
        $this->arrival['DelaySec'] = getByte($byteString[37]);

        //TFW Departure
        $this->departure['Stop'] = lookup(Constants::$FG_ABORT, ord($byteString[44]));
        $this->departure['Trigger'] = new Trigger(substr($byteString, 38), $TIE);

        //TFW arr/dep methods
        $this->arrival['MethodA'] = getByte($byteString[48]);
        $this->departure['MethodA'] = getByte($byteString[50]);
        $this->arrival['MethodB'] = getByte($byteString[52]);
        $this->departure['MethodB'] = getByte($byteString[54]);
        $this->arrival['MethodAon'] = getByte($byteString[49]);
        $this->departure['MethodAon'] = getByte($byteString[51]);
        $this->arrival['MethodBon'] = getByte($byteString[53]);
        $this->departure['MethodBon'] = getByte($byteString[55]);

        //TFW Orders
        foreach (array(56, 74, 92) as $o) {
            $this->orders[] = new FGOrder(substr($byteString, $o, 18));
        }

        //TFW Navigation
        $nav = array();
        $navStr = substr($byteString, 120, 90);
        $enabled = substr($byteString, 210); //last 30 bytes are booleans for the enabled with a 00 afterwards.
        for ($i = 0; $i < 15; $i++) {
            $nav[$i] = array();
            foreach (array('x' => 0, 'y' => 30, 'z' => 60) as $key => $offset) {
                $coord = getShort($navStr, $offset + ($i * 2));
                $nav[$i][$key] = $coord / 160;
            }
            $nav[$i]['on'] = ord($enabled[$i * 2]);
        }
        $this->navigation = $nav;

        $this->navigation = $this->departure = $this->win = $this->orders = array();
    }

    public function isInDifficultyLevel($difficulty)
    {
        if (!$this->arrives()) {
            return false;
        }
        $fgDiff = $this->arrival['Difficulty'];
        if ($fgDiff === 'All') {
            return true;
        }
        return strpos($fgDiff, $difficulty) !== false;
    }

    public function arrives()
    {
        $trigger = $this->arrival['TriggerA'];
        if ($fg = $trigger->getFG()) {
            if ($fg->isPlayerCraft() && $trigger->condition === 'Destroyed' && $trigger->amount === '100%') {
                error_log($this->__toString() . ' trigger is player and 100% destoryerd');
                error_log($this->arrival['TriggerA']);
                error_log($fg);
                return false;
            }
        }
        return true;
    }

    public function isPlayerCraft()
    {
        return $this->general['PlayerCraft'] > 0;
    }

    public function getLength()
    {
        return self::FLIGHT_GROUP_LENGTH;
    }

    public function getLabel()
    {
        return $this->general['ShipType']->Name . ' ' . $this->general['Name'];
    }

    public function __toString()
    {
        return (
            count($this) .
            'x ' .
            $this->general['ShipType']->Name .
            ' ' .
            $this->general['Name'] .
            ' ' .
            $this->general['AI'] .
            ($this->general['Warheads'] != "None" ? ' (' . $this->general['Warheads'] . ')' : "") .
            " radio " .
            $this->general['ObeysRadio']
        );
    }

    public function count()
    {
        $count = (int) $this->general['Ships'] * ((int) $this->general['Waves'] + 1);
        switch ($this->general['ShipType']->Name) {
            case 'Class 1 Mine':
            case 'Class 2 Mine':
            case 'Class 3 Mine':
                $count *= 2;
        }
        return $count;
    }

    public function pointValue($difficulty = null)
    {
        $pts = count($this) * $this->general['ShipType']->pointValue();
        if ($difficulty === 'Easy') {
            $pts = round($pts * 0.75);
        }
        if ($difficulty === 'Hard') {
            $pts = round($pts * 1.25);
        }

        $pts = round($pts * 1.125, 2); //collision on
        return $this->isFriendly() ? -10000 : $pts;
    }

    public function assetValue()
    {
        $pts = count($this) * $this->general['ShipType']->pointValue();
        $aiMultiplier = array('Rookie' => 1, 'Novice' => 1, 'Veteran' => 2, 'Officer' => 3, 'Ace' => 4, 'Top Ace' => 5);
        $pts *= $aiMultiplier[$this->general['AI']];
        if ($this->general['Warheads'] !== 'None') {
            $pts *= 2;
        }
        return $pts;
    }

    public function isFriendly()
    {
        return $this->general['PlayerCraft'] > 0 || ($this->general['IFF'] && $this->general['IFF']->imperial());
    }

    public function isHostile()
    {
        return $this->general['IFF'] && $this->general['IFF']->isHostile();
    }

    public function getIFF()
    {
        return isset($this->general['IFF']) ? $this->general['IFF'] : null;
    }

    public function maxWarheads()
    {
        if ($this->general['Warheads'] === 'None') {
            return 0;
        }

        $missiles = $this->general['ShipType']->missileCount();
        $status = $this->general['Status'];
        switch ($status) {
            case '2x missiles':
                return $missiles * 2;
            case 'half missiles':
                return ceil($missiles * 0.5);
            default:
                return $missiles;
        }
    }

    public function destroyable()
    {
        $invincibleGoals = array('Captured', 'Survived', 'Completed Primary Goals', 'Completed Mission');
        return (
            !in_array($this->goals['PrimaryWhat'], $invincibleGoals) &&
            !in_array($this->goals['SecondaryWhat'], $invincibleGoals) &&
            !in_array($this->goals['BonusWhat'], $invincibleGoals) &&
            $this->general['AI'] !== 'Top Ace' &&
            $this->destroyable
        );
    }

    public function captureable()
    {
        return (
            $this->goals['PrimaryWhat'] === 'Captured' ||
            $this->goals['SecondaryWhat'] === 'Captured' ||
            $this->goals['BonusWhat'] === 'Captured' ||
            $this->captureable
        );
    }

    public function captureCount()
    {
        $count = 0;
        foreach (array('Primary', 'Secondary', 'Bonus') as $goal) {
            if ($this->goals[$goal . 'What'] === 'Captured') {
                switch ($this->goals[$goal . 'Who']) {
                    case 'The special craft':
                        $count = 1;
                        break;
                    case '50%':
                        $count = ceil($this->count() / 2);
                        break;
                    case '100%':
                        $count = $this->count();
                        break;
                }
            }
        }
        return min($count, $this->count());
    }

    public function invincible()
    {
        return $this->general['AI'] === 'Top Ace';
    }

    public function isFighter()
    {
        return $this->general['ShipType']->isFighter();
    }

    public function isReinforcement()
    {
        return (
            $this->arrival['TriggerA']->condition === 'Reinforced' ||
            $this->arrival['TriggerB']->condition === 'Reinforced'
        );
    }
}
