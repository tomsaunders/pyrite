<?php
namespace XvT;

class XvTMission extends \MissionFormat {
    public function processMissionFile($hex){
        $remaining = $this->readHeader($hex);

    }

    protected function readHeader($hex){
        $this->header = new Header($hex);
        return substr($hex, $this->header->getLength());
    }

    function printDump(){
        return array(
            'filename' => $this->filename,
            'header' => $this->header
        );
    }
}