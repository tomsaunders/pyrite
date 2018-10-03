<?php
namespace Pyrite\XWA;

class ScoreKeeper {
	private $TIE;

    /** @var FlightGroup */
	private $playerCraft = array();
	private $globalGoals = array();
    private $fgGoals = array();
	private $fgs = array();
    private $goalTypes = array('Primary' => array(), 'Secondary' => array(), 'Bonus' => array());
    private $goalPoints = 250;
    private $badBonus = FALSE;
    private $invincible = array();
    private $difficultyFilter;

	public $total = 0;
	public $player = null;
	public $warhead = null;

	public function __construct(Mission $TIE, $difficulty = 'Hard'){
		$this->TIE = $TIE;
        $this->difficultyFilter = $difficulty;
        if ($this->TIE->valid()){
            $this->process();
        }
	}

	public function process(){
		/** @var $fg FlightGroup */
		foreach ($this->TIE->flightGroups as $idx => $fg){
			if (!$fg->isInDifficultyLevel($this->difficultyFilter)) continue;

            $name = (string)$fg;
            $points = $fg->pointValue($this->difficultyFilter);

            if ($points > 0){
                $this->total += $points;
                $this->fgs[] = $name . ': ' . $points;
            } else {
                $this->fgs[] = $name . ' - no points';
            }
//            $this->fgs[] = $fg->describeArrival();

			/** @var $goal GoalFG */
			foreach ($fg->FGGoals as $goal){
				if ($goal->getPoints()){
					$this->fgGoals[] = $fg . " - " . $goal;
					$this->total += $goal->getPoints();
				}
			}

            if ($fg->isPlayerCraft()){
                $this->playerCraft[] = $fg;
            }
		}

		//TODO global goals
	}

	public function printDump(){
        $goals = array("Primary" =>  "{$this->goalPoints} pts");
        $this->total += $this->goalPoints;
        $craft = array();
        $pcMax = array();
        if (!empty($this->playerCraft)){
            foreach ($this->playerCraft as $pc) {
                $craft[] = (string)$pc;
                $hangarPoints = $pc->pointValue('Medium', FALSE) * -1; //ignore friendly filter //always just get medium points
//
                $pcMax[] = $hangarPoints;
                $craft[] = 'Hanger/hyper points: ' . $hangarPoints . ' pts';
                $this->player = (string)$pc;
            }
//            $this->warhead = $this->playerCraft->general['Warheads'];
        } else {
            $craft[] = 'Player craft not found';
        }
        $this->total += count($pcMax) ? max($pcMax) : 0;

        return array(
			'Difficulty' => $this->difficultyFilter,
			'Enemies' => $this->fgs,
			'Craft options' => $craft,
			'Goals' => array_merge($goals, $this->globalGoals),
			'FGGoals' => $this->fgGoals,
			'Total Potential Score' => $this->total . ' pts',
		);
	}

} 