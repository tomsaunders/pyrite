<?php
namespace Pyrite\XvT;

class ScoreKeeper {
	private $TIE;

    /** @var FlightGroup */
	private $playerCraft = array();
	private $globalGoals = array();
    private $fgGoals = array();
    private $fgs = array();
    private $oths = array();
    private $goalTypes = array('Primary' => array(), 'Secondary' => array(), 'Bonus' => array());
    private $goalPoints = 10000;
    private $badBonus = FALSE;
    private $invincible = array();
    private $difficultyFilter;

	public $total = 0;
	public $player = null;
	public $warhead = null;
    public $bonus = 0;

	public function __construct(Mission $TIE, $difficulty = 'Hard'){
		$this->TIE = $TIE;
        $this->difficultyFilter = $difficulty;
        if ($this->TIE->valid()){
            $this->process();
        }
	}

	public function process(){
        /** @var $team1GG GlobalGoal */
        $team1GG = $this->TIE->globalGoals[0];
        $types = array(0 => 'Primary', 1=> 'Prevent', 2 => 'Bonus');

        foreach ($types as $idx => $type){
            /** @var $gg GoalGlobal */
            $gg = $team1GG->Goals[$idx];
            if ($p = $gg->getPoints()){
                $this->globalGoals[$type] = strval($gg);
                if ($p > 0){
                $this->total += $p;
                $this->bonus += $p;
                }
            }
        }

		/** @var $fg FlightGroup */
		foreach ($this->TIE->flightGroups as $idx => $fg){
			if (!$fg->isInDifficultyLevel($this->difficultyFilter)) continue;

            $name = (string)$fg;
            $points = $fg->pointValue($this->difficultyFilter);

            if ($points > 0){
                $this->total += $points;
                $this->fgs[] = $name . ': ' . $points;
            } else {
               $this->oths[] = $name . ' - no points';
            }

			/** @var $goal GoalFG */
			foreach ($fg->Goals as $goal){
				if ($goal->getPoints()){
					$this->fgGoals[] = $fg . " - " . $goal;
					$this->total += $goal->getPoints();
                    $this->bonus += $goal->getPoints();
				}
			}

//            $win = $fg->goals;
//            foreach ($this->goalTypes as $type => &$goals){
//                if ($win[$type . 'What'] !== 'None' && $win[$type . 'What'] !== 'Always'){
//                    $goal = $win[$type . 'Who'] . ' Flight Group ' . $fg . ' must be ' . $win[$type . 'What'];
//                    if (isset($win[$type . 'Pts'])){
//                        $goal .= ' (' . $win[$type . 'Pts'] . ' pts)';
//                        $pts = (int)$win[$type. 'Pts'];
//                        if ($pts < 0){
//                            $this->badBonus = TRUE;
//                        } else {
//                            $this->total += $pts;
//                        }
//                    }
//                    $goals[] = $goal;
//                }
//            }

            if ($fg->isPlayerCraft()){
                $this->playerCraft[] = $fg;
            }
		}
	}

	public function printDump(){
        $goals = array("Primary" =>  "{$this->goalPoints} pts");
        $this->total += $this->goalPoints; //primary goals
//        if (count($this->goalTypes['Secondary'])){
//            $this->total += $goalPoints;
//            $goals[] = "Secondary goals: $goalPoints pts";
//        } else {
//            unset($this->goalTypes['Secondary']);
//        }
//        if ($c = count($this->goalTypes['Bonus'])){
//            $this->total += 3100;
//            if ($this->badBonus){
//                $goals[] = 'Some bonus goals have negative points and should not be completed';
//                $goals[] = "All positive bonus goals: 3100 pts";
//            } else {
//                $goals[] = "All $c bonus goals: 3100 pts";
//            }
//        } else {
//            unset($this->goalTypes['Bonus']);
//        }
        $craft = array();
        $pcMax = array();
        if (!empty($this->playerCraft)){
            foreach ($this->playerCraft as $pc) {
                $craft[] = $pc->label();
                $hangarPoints = $pc->pointValue('Medium', FALSE) * -1; //ignore friendly filter //always just get medium points
//
                $pcMax[] = $hangarPoints;
                $craft[] = 'Hanger/hyper points: ' . $hangarPoints . ' pts';
                $this->player = $pc->label();
            }
//            $this->warhead = $this->playerCraft->general['Warheads'];
        } else {
            $craft[] = 'Player craft not found';
        }
        $this->total += max($pcMax);

        return array(
            'Difficulty' => $this->difficultyFilter,
            'Others' => $this->oths,
			'Enemies' => $this->fgs,
			'Craft options' => $craft,
			'Goals' => array_merge($goals, $this->globalGoals),
			'FGGoals' => $this->fgGoals,
			'Total Potential Score' => $this->total . ' pts',
		);
	}

	public function bonus(){
		return array_merge($this->globalGoals, $this->fgGoals);
	}

} 