<?php

namespace Pyrite\XWING;

use Pyrite\Hex;
use Pyrite\MissionFormat;

class Mission extends MissionFormat {
    public $filename;
    public $spaceObjects = array();

    public $remaining;
    public $unknownSet = array();

    public $lookups = array(
    );

    const FG_LENGTH = 148;

    function __construct($source, $justHeader = FALSE){
        if (strlen($source) < 100){
            $hex = file_get_contents($source);
            $this->filename = $source;
        } else {
            $hex = $source;
        }
        $remaining = $this->readHeader($hex);
        if ($this->header->valid && !$justHeader){
            $remaining = $this->readFlightGroups($remaining);
            $remaining = $this->readObjects($remaining);
        }
        $this->remaining = $remaining;
        $this->remaining = '';
    }

    function valid(){
        return $this->header->valid;
    }

    function readFlightGroups($remaining){
        for ($i = 0; $i < $this->header->flightGroupCount; $i++){
            $this->flightGroups[] = $this->readFlightGroup(substr($remaining, self::FG_LENGTH * $i, self::FG_LENGTH));
        }
        return substr($remaining, self::FG_LENGTH * $this->header->flightGroupCount);
    }

    function readObjects($remaining){
        $this->spaceObjects = array();
        return $remaining;
    }

    function readFlightGroup($hex){
		$fg = new FlightGroup($hex, $this);
		return $fg;
    }

    function lookup($key, $value){
        $value = getByte($value);

        if (!isset($this->lookups[$key])) return "Unknown lookup $key";
        if (!isset($this->lookups[$key][$value])) return "Unknown lookup $key / $value";
        return $this->lookups[$key][$value];
    }

    function lookupShort($key, $hex, $startPos = null){
        $value = getShort($hex, $startPos);

        if (!isset($this->lookups[$key])) return "Unknown lookup $key";
        if (!isset($this->lookups[$key][$value])) return "Unknown lookup $key / $value";
        return $this->lookups[$key][$value];
    }

    function lookupSwitch($switch, $value){
        $value = getByte($value);
        return "Unknown lookup switch $value";
    }

    function getFG($idx){
        return isset($this->flightGroups[$idx]) ? $this->flightGroups[$idx] : null;
    }

	function getFGString($idx){
		$fg = $this->flightGroups[$idx];
		return (string)$fg;
	}

    function readHeader($hex){
        $this->header = new Header($hex);
        return substr($hex, $this->header->getLength());
    }

    function printDump(){
        return array(
            'filename' => $this->filename,
            'header' => (string)$this->header,
            'Flight Groups' => $this->flightGroups,
            'Objects' => $this->spaceObjects,
            'Remaining' => Hex::hexToStr($this->remaining)
        );
    }
}