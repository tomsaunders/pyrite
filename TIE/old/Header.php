<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;

class Header implements Byteable{

    public $valid;

    public $flightGroupCount;
    public $messageCount;
    public $briefingOfficers;
    public $capturedOnEject;

    /**
     * The TIE mission header is 23 bytes long
     * #0-1 2 bytes FF (constant)
     * #2   1 byte # flight groups
     * #4   1 byte # of messages
     * #6   1 byte unknown purpose, constant value of 3
     * #8   1 byte unknown purpose, value 2A for first bomber combat chamber
     * #9   1 byte unknown purpose, value 1 for missions 3-2, 8-2, 8-3, 8-5, 8-6
     * #10  1 byte who should be present at the briefing (both, flight, secret, none)
     * #13  1 byte behaviour after dying [0 = rescued, 1 = captured]
     * remaining 9 bytes are always 00, in every official TIE battle
     */
    const HEADER_LENGTH = 23;
    const HEADER_FG = 2;
    const HEADER_MSG = 4;
    const HEADER_THREE = 6;
    const HEADER_BRIEF = 10;
    const HEADER_CAPTURED = 13;

    const BRIEFING_NONE = 0;
    const BRIEFING_BOTH_OFFICERS  = 1;
    const BRIEFING_FLIGHT_OFFICER = 2;
    const BRIEFING_SECRET_ORDER   = 3;

    public function __construct($hex){
        $this->valid = substr($hex, 0, 2) === (chr(255) . chr(255));
        $this->flightGroupCount = getShort($hex, self::HEADER_FG      );
        $this->messageCount     = getShort($hex, self::HEADER_MSG     );
        $this->briefingOfficers = getByte ($hex, self::HEADER_BRIEF   );
        $this->capturedOnEject  = getBool ($hex, self::HEADER_CAPTURED);
    }

    public function getLength(){
        return self::HEADER_LENGTH;
    }

    public function __toString(){
        if (!$this->valid){
            return "Mission is in invalid format";
        }
        $str = "Mission has {$this->flightGroupCount} FGs and {$this->messageCount} messages. ";
        switch ($this->briefingOfficers){
            case self::BRIEFING_NONE:           $str .= "No briefing officer present"; break;
            case self::BRIEFING_BOTH_OFFICERS:  $str .= "Flight Officer and Secret Order present"; break;
            case self::BRIEFING_FLIGHT_OFFICER: $str .= "Flight Officer briefing"; break;
            case self::BRIEFING_SECRET_ORDER:   $str .= "Secret Order briefing"; break;
        }
        $str .= "   " . (($this->capturedOnEject) ? "Captured" : "Rescued") . " on eject";
        return $str;
    }

}