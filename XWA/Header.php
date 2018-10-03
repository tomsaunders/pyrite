<?php

namespace Pyrite\XWA;

use Pyrite\Byteable;
use Pyrite\Hex;

class Header implements Byteable {

    public $platformID;
    public $flightGroupCount;
    public $messageCount;
    public $IFFNames = array();
    public $regionNames = array();
    public $globalCargos = array();
    public $globalGroupNames = array();
    public $hangar;
    public $timeLimitMinutes;
    public $endMissionWhenComplete;
    public $briefingOfficer;
    public $briefingLogo;
    public $hex;

    const HEADER_LENGTH = 9200;
    const HEADER_START = 0;
    const HEADER_FG = 2;
    const HEADER_MSG = 4;
    const HEADER_IFF = 20;
    const HEADER_REGION = 100;
    const HEADER_CARGO = 628;
    const HEADER_GG = 2868;
    const HEADER_HANGAR = 9132;
    const HEADER_TIME = 9134;
    const HEADER_END = 9135;
    const HEADER_OFFICER = 9136;
    const HEADER_LOGO = 9137;

    const IFF_COUNT = 4;
    const IFF_LENGTH = 20;
    const REGION_COUNT = 4;
    const REGION_LENGTH = 132;
    const CARGO_COUNT = 16;
    const CARGO_LENGTH = 140;
    const GG_COUNT = 16;
    const GG_LENGTH = 87;

    public function __construct($byteString){
        $this->platformID       = getShort($byteString, self::HEADER_START);
        $this->flightGroupCount = getShort($byteString, self::HEADER_FG);
        $this->messageCount     = getShort($byteString, self::HEADER_MSG);

        for ($i = 0; $i < self::IFF_COUNT; $i++){
            $this->IFFNames[] = getString($byteString, self::HEADER_IFF + $i * self::IFF_LENGTH, self::IFF_LENGTH);
        }

        for ($r = 0; $r < self::REGION_COUNT; $r++){
            $this->regionNames[] = getString($byteString, self::HEADER_REGION + $r * self::REGION_LENGTH, self::REGION_LENGTH);
        }

        for ($c = 0; $c < self::CARGO_COUNT; $c++){
            $this->globalCargos[] = new GlobalCargo(substr($byteString, self::HEADER_CARGO + $c * GlobalCargo::LENGTH, GlobalCargo::LENGTH));
        }

        for ($g = 0; $g < self::GG_COUNT; $g++){
            $this->globalGroupNames[] = getString($byteString, self::HEADER_GG + $g * self::GG_LENGTH, self::GG_LENGTH);
        }

        $this->hangar                   = getByte($byteString, self::HEADER_HANGAR);
        $this->timeLimitMinutes         = getByte($byteString, self::HEADER_TIME);
        $this->endMissionWhenComplete   = getBool($byteString, self::HEADER_END);
        $this->briefingOfficer          = getByte($byteString, self::HEADER_OFFICER);
        $this->briefingLogo             = getByte($byteString, self::HEADER_LOGO);
        $this->hex = Hex::hexToStr($byteString);
    }

    public function getLength(){
        return self::HEADER_LENGTH;
    }
}