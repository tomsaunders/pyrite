<?php
namespace XWA;

class GlobalCargo {
    use \HexDecoder;

    const LENGTH = 140;
    const NAME_LENGTH = 64;
    public $cargo;

    public function __construct($byteString){
        $this->cargo = $this->getString($byteString, 0, self::NAME_LENGTH);
    }
} 