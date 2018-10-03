<?php
namespace Pyrite\TIE;

class Battle {
    private $print = array();
    public $filenames = array();

    public function __construct($path, $missionCount){
        $l = 1;
        $lfd = new LFD($path . "/battle$l.lfd");
        $this->filenames = $lfd->filenames;

        while (count($this->filenames) < $missionCount){
            $l++;
            $lfd = new LFD($path . "/battle$l.lfd");
            $this->filenames = array_merge($this->filenames, $lfd->filenames);
        }
    }

    public function printDump(){
        return $this->filenames;
    }
} 