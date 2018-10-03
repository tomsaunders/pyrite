<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;

class IFFEntry implements Byteable {
    private $isHostile = FALSE;
    private $name = '';
    private $builtin;

    public function __construct($hex, $builtin = FALSE){
        if (empty($hex)) return;
        $this->name = $hex;
        if (substr($hex, 0, 1) === '1') {
            $this->isHostile = TRUE;
            $this->name = substr($this->name, 1);
        }
        $this->builtin = $builtin;
    }

    public function getLength(){
        return IFF::IFF_LENGTH;
    }

	public function __toString(){
		return is_string($this->name) ? $this->name : '';
	}

    public function imperial(){
        return $this->name === 'Imperial' && $this->builtin;
    }

    public function isHostile(){
        return $this->isHostile;
    }
}