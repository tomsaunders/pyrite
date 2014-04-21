<?php

namespace Pyrite\TIE;

class BriefingEvent {
    public $time;
    public $type;
    public $variables = array();

    private $EventTypes = array(
        3  => array('Name' => 'Page Break',     'Variables' => array()),
        4  => array('Name' => 'Title Text',     'Variables' => array('String#')),
        5  => array('Name' => 'Caption Text',   'Variables' => array('String#')),
        6  => array('Name' => 'Move Map',       'Variables' => array('X', 'Y')),
        7  => array('Name' => 'Zoom Map',       'Variables' => array('X', 'Y')), //TODO zoom level??
        8  => array('Name' => 'Clear FG Tags',  'Variables' => array()),
        9  => array('Name' => 'FG Tag 1',       'Variables' => array('Flight Group')),
        10 => array('Name' => 'FG Tag 2',       'Variables' => array('Flight Group')),
        11 => array('Name' => 'FG Tag 3',       'Variables' => array('Flight Group')),
        12 => array('Name' => 'FG Tag 4',       'Variables' => array('Flight Group')),
        13 => array('Name' => 'FG Tag 5',       'Variables' => array('Flight Group')),
        14 => array('Name' => 'FG Tag 6',       'Variables' => array('Flight Group')),
        15 => array('Name' => 'FG Tag 7',       'Variables' => array('Flight Group')),
        16 => array('Name' => 'FG Tag 8',       'Variables' => array('Flight Group')),
        17 => array('Name' => 'Clear Text Tags','Variables' => array()),
        18 => array('Name' => 'Text Tag 1',     'Variables' => array('Tag#', 'TextTagColor', 'X', 'Y')),
        19 => array('Name' => 'Text Tag 2',     'Variables' => array('Tag#', 'TextTagColor', 'X', 'Y')),
        20 => array('Name' => 'Text Tag 3',     'Variables' => array('Tag#', 'TextTagColor', 'X', 'Y')),
        21 => array('Name' => 'Text Tag 4',     'Variables' => array('Tag#', 'TextTagColor', 'X', 'Y')),
        22 => array('Name' => 'Text Tag 5',     'Variables' => array('Tag#', 'TextTagColor', 'X', 'Y')),
        23 => array('Name' => 'Text Tag 6',     'Variables' => array('Tag#', 'TextTagColor', 'X', 'Y')),
        24 => array('Name' => 'Text Tag 7',     'Variables' => array('Tag#', 'TextTagColor', 'X', 'Y')),
        25 => array('Name' => 'Text Tag 8',     'Variables' => array('Tag#', 'TextTagColor', 'X', 'Y')),
        34 => array('Name' => 'End Briefing',   'Variables' => array())
    );

    private $TextTagColor = array('Green', 'Red', 'Purple', 'Blue', 'Red', 'Light Red', 'Gray', 'White');

    function __construct($hex){
        $this->time = getShort($hex, 0);
        $type = getShort($hex, 2);
        if (isset($this->EventTypes[$type])){
            $this->type = $this->EventTypes[$type];
        } else {
            $this->type = array('Name' => 'Unknown' . $type, 'Variables' => array());
        }

        for ($i = 0; $i < count($this->type['Variables']); $i++){
            $this->variables[] = getShort($hex, 2 + ($i * 2));
        }
        $this->length = count($this->variables) * 2 + 4; //a short for each variable plus time and type;
    }

    public function getLength(){
        return $this->length;
    }

    function __toString(){
        return $this->type['Name'] . ' at ' . $this->time . ' ' . implode(' , ', $this->variables);
    }
} 