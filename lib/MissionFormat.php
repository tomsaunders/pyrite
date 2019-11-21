<?php

namespace Pyrite;

abstract class MissionFormat {
    public $header;
    public $filename;
    public $flightGroups = array();
    public $messages = array();
    public $briefing = array();
    public $globalGoals = array();

    public abstract function valid();
}