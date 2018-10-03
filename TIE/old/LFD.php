<?php
namespace Pyrite\TIE;

class LFD {
    private $print = array();
    public $filenames = array();

    public function __construct($filename){
        $lfd = file_get_contents($filename);
        $rmap = substr($lfd, 0, 48);
        $rest = substr($lfd, 48);
        $header = substr($rest, 0, 16);
        $count = getShort($rest, 16);
        $this->print[] = $count;
        $rest = substr($rest, 18);
        $battles = null;
        for ($i = 0; $i < 4; $i++){
            $len = getShort($rest);
            $this->print[] = $battles = substr($rest, 2, $len);
            $rest = substr($rest, $len + 2);
        }
        $battles = substr($battles, 0, -2);

        $this->filenames = explode(chr(0), $battles);
    }

    public function printDump(){
        return $this->filenames;
    }
} 