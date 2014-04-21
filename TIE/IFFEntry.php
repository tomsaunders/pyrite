<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;

class IFFEntry implements Byteable {
    private $isHostile = FALSE;
    private $name = '';

    public function __construct($hex){
        if (empty($hex)) return;
        $this->name = $hex;
        if (substr($hex, 0, 1) === '1') {
            $this->isHostile = TRUE;
            $this->name = substr($this->name, 1);
        }
    }

    public function getLength(){
        return IFF::IFF_LENGTH;
    }
} 