<?php
namespace Pyrite\XWING;

class Complexity {
	private $TIE;

	private $playerCraft = null;
	private $globalGoals = array();
	private $fgs = array();
    private $goalTypes = array('Primary' => array(), 'Secondary' => array());
    private $points = array('Friendly' => 0, 'Hostile' => 0);

    public $total = 0;

	public function __construct(Mission $TIE){
		$this->TIE = $TIE;
        if ($this->TIE->valid()){
            $this->process();
        }
	}

	public function process(){
	}

	public function printDump(){
    }
} 