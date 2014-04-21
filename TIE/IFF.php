<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;

class IFF implements \Countable, Byteable {

    const IFF_LENGTH = 12;
    const IFF_COUNT  = 4;

    private $iffList = array();

    public function __construct($hex){
        for ($i = 0; $i < self::IFF_COUNT; $i++){
            $this->iffList[] = new IFFEntry(getString($hex, self::IFF_LENGTH * $i, self::IFF_LENGTH));
        }
    }

    public function count(){
        $count = 0;
        foreach ($this->iffList as $iff){
            if (!empty($iff->name)){
                $count++;
            }
        }
        return $count;
    }

    public function getLength(){
        return self::IFF_COUNT * self::IFF_LENGTH;
    }

    public function __toString(){
        return $this->count() ? print_r($this->iffList,1) : "No custom IFFs";
    }
} 