<?php
namespace Pyrite\TIE;

use Pyrite\Summary;

class GlobalGoal extends GlobalGoalBase implements Summary
{
	public function summaryHash(){
		$triggas = [];
		/** @var Trigger $trig */
		foreach ($this->Triggers as $n => $trig){
			$trig->TIE = $this->TIE;
			if ($trig->Condition === 10 || ($n === 1 && $trig->Condition === 0)){
				continue;
			}
			$triggas[] = (string)$trig;
		}
		if (count($triggas) === 0){
			return false;
		}

		$glue = $this->Trigger1OrTrigger2 ? 'OR' : 'AND';

		return [
			'Triggers' => implode("<br />$glue<br />", $triggas),
		];
	}
}
