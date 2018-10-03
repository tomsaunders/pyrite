<?php
namespace Pyrite\XWING;

use Pyrite\Byteable;

/**
 * The XWING mission header is 206 bytes long
 * Version	                2 bytes	    integer	Always 2
 * Mission Time Limit	    2 bytes	    integer	In minutes
 * End event	            2 bytes	    integer	0 = Rescued 1 = Captured (or Clear Laser Towers) 5 = Hit Exhaust Port
 * ??	                    2 bytes	    0
 * Mission Location	        2 bytes	    integer	0 = deep space 1 = death star surface
 * Completion Message 1	    64 bytes	string	pad with zeros
 * Completion Message 2	    64 bytes	string
 * Completion Message 3	    64 bytes	string
 * Number of flight groups	2 bytes	    integer
 * Number of objects	    2 bytes	    integer
 * @package Pyrite\XWING
 */
class Header implements Byteable{
    public $valid;
    public $version;
    public $timeLimit;
    public $endEvent;
    public $unknown;
    public $location;
    public $messages;
    public $flightGroupCount;
    public $objectCount;

    const HEADER_LENGTH = 206;
    const MSG_LENGTH    = 64;
    const MSG_COUNT     = 3;

    const HEADER_TIME   = 2;
    const HEADER_END    = 4;
    const HEADER_UNK    = 6;
    const HEADER_LOC    = 8;
    const HEADER_MSG    = 10;
    const HEADER_FG     = 202;
    const HEADER_OBJ    = 204;

    const VALID_VERSION = 2;

    const END_RESCUED   = 0;
    const END_CAPTURED  = 1;
    const END_EXHAUST   = 5;

    const LOC_SPACE     = 0;
    const LOC_DEATHSTAR = 1;

    public function __construct($hex){
        $this->version          = getShort($hex);
        $this->timeLimit        = getShort($hex, self::HEADER_TIME);
        $this->endEvent         = getShort($hex, self::HEADER_END );
        $this->unknown          = getShort($hex, self::HEADER_UNK );
        $this->location         = getShort($hex, self::HEADER_LOC );
        $this->flightGroupCount = getShort($hex, self::HEADER_FG  );
        $this->objectCount      = getShort($hex, self::HEADER_OBJ );
        for ($m = 0; $m < self::MSG_COUNT; $m++){
            $this->messages[] = getString($hex, self::HEADER_MSG + self::MSG_LENGTH * $m);
        }
        $this->valid = $this->version == self::VALID_VERSION;
    }

    public function getLength(){
        return self::HEADER_LENGTH;
    }

    public function __toString(){
        if (!$this->valid){
            return "Mission is in invalid format";
        }
        $out = array("Mission has {$this->flightGroupCount} FGs and {$this->objectCount} objects");
        switch ($this->endEvent){
            case self::END_RESCUED:     $out[] = "Rescued on end"; break;
            case self::END_CAPTURED:    $out[] = "Captured on end"; break;
            case self::END_EXHAUST:     $out[] = "Exhaust port ending"; break;
        }
        switch ($this->location){
            case self::LOC_SPACE:       $out[] = 'Deep space setting'; break;
            case self::LOC_DEATHSTAR:   $out[] = 'Death Star setting'; break;
        }
        foreach ($this->messages as $i => $message){
            if (!empty($message))       $out[] = "Msg $i: $message";
        }
        return implode(". ", $out);
    }

}