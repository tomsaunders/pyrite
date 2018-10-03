<?php
namespace Pyrite\TIE;

class Golf {
	private $TIE;

	private $playerCraft = null;
	private $globalGoals = array();
    private $fgGoals = array();
	private $fgs = array();
    private $goalTypes = array('Primary' => array(), 'Secondary' => array(), 'Bonus' => array());
    private $badBonus = FALSE;
    private $invincible = array();

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
		$difficultyFilter = 'Easy';

		foreach ($this->goalTypes as $type => &$goals){
			foreach ($this->TIE->globalGoals[$type] as $trigger){
				if ($trigger instanceof Trigger && $trigger->hasData()){
					$goals[] = (string)$trigger;
				}
			}
		}

		foreach ($this->TIE->flightGroups as $idx => $fg){
			if (!$fg->isInDifficultyLevel($difficultyFilter)) continue;

            $name = (string)$fg;
            $points = $fg->pointValue($difficultyFilter);

            if ($points > 0){
                if ($fg->captureable()) {
                    $points *= 5;
                    $name .= ' capturable ';
                } else if (!$fg->destroyable()){
                    $points = 0;
                    $name .= ' invincible or mission critical ';
                }
                $this->total += $points;
                $this->fgs[] = $name . ': ' . $points;
            }

            if ($fg->invincible()){
                $this->invincible[] = 'Invincible: ' . $fg;
            }

            $win = $fg->goals;
            foreach ($this->goalTypes as $type => &$goals){
                if ($win[$type . 'What'] !== 'None' && $win[$type . 'What'] !== 'Always'){
                    $goal = $win[$type . 'Who'] . ' Flight Group ' . $fg . ' must be ' . $win[$type . 'What'];
                    if (isset($win[$type . 'Pts'])){
                        $goal .= ' (' . $win[$type . 'Pts'] . ' pts)';
                        $pts = (int)$win[$type. 'Pts'];
                        if ($pts < 0){
                            $this->badBonus = TRUE;
                        } else {
                            $this->total += $pts;
                        }
                    }
                    $goals[] = $goal;
                }
            }

            if ($fg->isPlayerCraft()){
                $this->playerCraft = $fg;
            }
		}
	}

	public function printDump(){
        $goals = array('Primary goals: 2750 pts');
        $this->total += 7750; //primary goals
        if (count($this->goalTypes['Secondary'])){
            $this->total += 7750;
            $goals[] = 'Secondary goals: 2750 pts';
        } else {
            unset($this->goalTypes['Secondary']);
        }
        if ($c = count($this->goalTypes['Bonus'])){
            if ($this->badBonus){
                $goals[] = 'Some bonus goals have negative points!!!';
            } else {
                $this->total += 3100;
                $goals[] = "All $c bonus goals: 3100 pts";
            }
        } else {
            unset($this->goalTypes['Bonus']);
        }
        $craft = array('Player craft: ' . (string)$this->playerCraft);
        if ($this->playerCraft){
            $warheadPts = $this->playerCraft->maxWarheads() * 50;
            $this->total += $warheadPts;
            $craft[] = 'Maximum warhead points: ' . $warheadPts . ' pts';
            $this->player = (string)$this->playerCraft;
            $this->warhead = $this->playerCraft->general['Warheads'];
        } else {
            $craft[] = 'Player craft not found';
        }

        return array('Points' => array_merge($this->fgs, $goals, $craft, array('Total score: ' . $this->total . ' pts')), 'Goals' => $this->goalTypes);
	}

} 