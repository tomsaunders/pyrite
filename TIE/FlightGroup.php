<?php

namespace TIE;

class FlightGroup implements Byteable{
    public $general;
    public $goals;
    public $arrival;
    public $departure;
    public $orders;
    public $navigation;

    private $fg = array();

    const FLIGHT_GROUP_LENGTH = 292;

    public function __construct(){

    }

    public function getLength(){
        return self::FLIGHT_GROUP_LENGTH;
    }

    public function __toString(){
        return "FG n";
    }
} 