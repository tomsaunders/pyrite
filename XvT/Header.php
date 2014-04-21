<?php

namespace XvT;

use TIE\Byteable;
use TIE\length;

class Header implements Byteable {
    use \HexDecoder;

    public $platformID;
    public $flightGroupCount;
    public $messageCount;
    public $unk1;
    public $unk2;
    public $unk3;
    public $unk4; //char16 BoP only
    public $unk5; //char16 BoP only
    public $missionType;
    public $unk6;
    public $timeLimitMinutes;
    public $timeLiimitSeconds;

    public $hex;

    const HEADER_LENGTH = 164;
    const HEADER_START = 0;
    const HEADER_FG = 2;
    const HEADER_MSG = 4;
    const HEADER_UNK = 6;
    const HEADER_TYPE = 100;
    const HEADER_TIME = 102;

    public function __construct($byteString){
        $this->platformID       = $this->getShort($byteString, self::HEADER_START);
        $this->flightGroupCount = $this->getShort($byteString, self::HEADER_FG);
        $this->messageCount     = $this->getShort($byteString, self::HEADER_MSG);
        $this->unk1             = $this->getByte($byteString , self::HEADER_UNK);
        $this->unk2             = $this->getByte($byteString , self::HEADER_UNK + 2);
        $this->unk3             = $this->getBool($byteString , self::HEADER_UNK + 5);
        $this->unk4             = $this->getByte($byteString , 40);
        $this->unk5             = $this->getByte($byteString , 80);
        $this->missionType      = $this->getByte($byteString , self::HEADER_MISSION);
        $this->unk6             = $this->getBool($byteString , self::HEADER_MISSION + 1);
        $this->timeLimitMinutes = $this->getByte($byteString , self::HEADER_TIME);
        $this->timeLiimitSeconds= $this->getByte($byteString , self::HEADER_TIME + 1);

        $this->hex = \Hex::hexToStr($byteString);
    }

    public function getLength()
    {
        return self::HEADER_LENGTH;
    }
}