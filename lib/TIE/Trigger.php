<?php

namespace Pyrite\TIE;

class Trigger extends TriggerBase {
	public function __toString() {
		if ($this->Condition === 0) {
			return 'Always';
		}
		$parts = [$this->getTriggerAmountLabel(), 'of', $this->getVariableTypeLabel()];

		if ($this->VariableType === 1) {
			$fg      = $this->TIE->FlightGroups[$this->Variable];
			$parts[] = (string)$fg;
		}

		$parts[] = 'must';
		$parts[] = $this->getConditionLabel();
		return implode(' ', $parts);
	}
}
