<?php
namespace Pyrite\XWA;

class Complexity {
	private $TIE;

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