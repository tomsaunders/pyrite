<?php
namespace Pyrite\TIE;

use Pyrite\Summary;

class Event extends EventBase implements Summary
{
	public $Briefing;

    protected function VariableCount()
    {
        static $counts = [
                3 => 0,
                4 => 1,
                5 => 1,
                6 => 2,
                7 => 2,
                8 => 0,
                9 => 1,
                10 => 1,
                11 => 1,
                12 => 1,
                13 => 1,
                14 => 1,
                15 => 1,
                16 => 1,
                17 => 0,
                18 => 4,
                19 => 4,
                20 => 4,
                21 => 4,
                22 => 4,
                23 => 4,
                24 => 4,
                25 => 4,
                34 => 0
            ];
        if (isset($counts[$this->EventType])) {
            return $counts[$this->EventType];
        } else {
            throw new \Error("Unknown count for {$this->EventType}");
        }
    }

    public function summaryHash(){
    	$notes = '';
    	if ($this->EventType == 4 || $this->EventType == 5){
    		$notes = (string)$this->Briefing->Strings[$this->Variables[0]];
		} else if ($this->EventType >= 9 && $this->EventType <= 16){
    		$notes = (string)$this->TIE->FlightGroups[$this->Variables[0]];
		} else if ($this->EventType >= 19 && $this->EventType <= 25){
    		$notes = (string)$this->Briefing->Tags[$this->Variables[0]];
		}
    	return [
    		'Type' => $this->getEventTypeLabel(),
			'At' => $this->Time,
			'Notes' => $notes
		];
	}
}
