<?php
class TIEMissionReport {
    private $TIE;
    public $report = array();

    public function __construct($TIE){
        $this->TIE = $TIE;
        $this->process();
    }

    private function process(){
        $this->processGoalMessages();
        $this->processFlightGroups();
        $this->processGlobalGoals();
    }

    private function processGlobalGoals(){
        foreach ($this->TIE->globalGoals as $type => $goals){
            foreach (array('TriggerA', 'TriggerB') as $key){
                $trigger = $goals[$key];
                if ($trigger['condition'] !== 'None'){
                    if ($trigger['vartype'] === 'Flight Group'){
                        $fg = $this->TIE->flightGroups[$trigger['varID']];
                        if ($fg['Arrival']['Difficulty'] !== 'All'){
                            $this->report[] = "Global $type Goal is only completeable on " . $fg['Arrival']['Difficulty'] . ' as it depends on FG ' . $fg['General']['Name'] . ' at position ' . $trigger['varID'];
                        }
                    }
                }
            }
        }
    }

    private function processGoalMessages(){
        foreach ($this->TIE->goalMessages as $key => $message){
            if (empty($message)) $this->report[] = "Goal message $key is empty";
        }
    }

    private function processFlightGroups(){
        foreach ($this->TIE->flightGroups as $fg){
            $name = $fg['General']['Name'];
            $shipType = $fg['General']['ShipType'];
            $isStarship = $shipType->isStarship();

            foreach ($fg['Orders'] as $order){
                if ($isStarship !== $order->isStarship()){
                    $this->report[] = "{$shipType->Name} $name has order {$order->Name} - starship order problem";
                }
            }
        }
    }

    public function printReport(){
        return $this->report;
    }
} 