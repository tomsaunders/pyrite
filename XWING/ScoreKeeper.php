<?php
namespace Pyrite\XWING;

class ScoreKeeper {
	private $TIE;

	private $playerCraft = null;
	private $goals = array();
	private $fgs = array();
    private $goalPoints = 1500;
    private $invincible = array();
    private $difficultyFilter;

	public $total = 0;
	public $player = null;
	public $warhead = null;

	public function __construct(Mission $TIE){
		$this->TIE = $TIE;
        if ($this->TIE->valid()){
            $this->process();
        }
	}

	public function process(){
		foreach ($this->TIE->flightGroups as $idx => $fg){
            $name = (string)$fg;
            $points = $fg->pointValue($this->difficultyFilter);

            if ($points > 0){
                if (!$fg->destroyable()){
                    $points = 0;
                    $name .= ' invincible or mission critical ';
                }
                $this->total += $points;
                $this->fgs[] = $name . ': ' . $points;
//                $this->fgs[] = $fg;
            }

            if ($fg->invincible()){
                $this->invincible[] = 'Invincible: ' . $fg;
            }

            if ($fg->objective !== 'None'){
                $this->goals[] = 'Flight Group ' . $fg . ' must be ' . $fg->objective;
            }

            if ($fg->isPlayerCraft()){
                $this->playerCraft = $fg;
            }
		}
	}

	public function printDump(){
        $goalPoints = $this->goalPoints;
        $this->total += $goalPoints; //primary goals

        $craft = array('Player craft: ' . (string)$this->playerCraft);
        if ($this->playerCraft){
            $warheadPts = $this->playerCraft->maxWarheads() * 50;
            $this->total += $warheadPts;
            $craft[] = 'Maximum warhead points: ' . $warheadPts . ' pts';
        } else {
            $craft[] = 'Player craft not found';
        }

        return array(
            'Points' => array_merge($this->fgs, $craft, array('Victory: 1500 pts', 'Total score: ' . $this->total . ' pts')),
            'Goals' => $this->goals
        );
	}

} 