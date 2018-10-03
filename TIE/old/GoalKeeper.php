<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 30/04/14
 * Time: 3:35 PM
 */

namespace Pyrite\TIE;

class GoalKeeper
{
    private $TIE;

    private $playerCraft = array();
    private $globalGoals = array();
    private $fgGoals = array();

    public function __construct(Mission $TIE)
    {
        $this->TIE = $TIE;
        if ($this->TIE->valid()) {
            $this->process();
        }
    }

    public function process()
    {
        $difficultyFilter = 'Easy';
        $goalFilter = array('Primary');
        if ($this->TIE->header->briefingOfficers === Header::BRIEFING_SECRET_ORDER) {
            $goalFilter[] = 'Secondary';
        }

        foreach ($goalFilter as $type) {
            foreach ($this->TIE->globalGoals[$type] as $trigger) {
                if ($trigger instanceof Trigger && $trigger->hasData()) {
                    $this->globalGoals[] = $type . ': ' . $trigger;
                }
            }
        }

        $MIS = new ShipType(chr(12));

        foreach ($this->TIE->flightGroups as $idx => $fg) {
            if (!$fg->isInDifficultyLevel($difficultyFilter)) {
                continue;
            }

            $win = $fg->goals;
            foreach ($goalFilter as $type) {
                if ($win[$type . 'What'] !== 'None') {
                    $this->fgGoals[] = $type . ': ' . $win[$type . 'Who'] . ' Flight Group ' . $fg . ' must be ' . $win[$type . 'What'];
                }
            }

            if ($fg->isPlayerCraft()) {
                $this->playerCraft[] = (string)$fg->general['ShipType'];
                $this->playerCraft[] = $fg->general['Warheads'];
            }
        }
    }

    public function HR()
    {
        return count($this->playerCraft) === 2 && $this->playerCraft[1] === 'Heavy rockets';
    }

    public function printDump()
    {
        return array_merge($this->playerCraft, $this->globalGoals, $this->fgGoals);
    }

    public function goals()
    {
        return array_merge($this->globalGoals, $this->fgGoals);
    }
}
