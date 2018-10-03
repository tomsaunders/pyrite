<?php
namespace Pyrite\TIE;

use Pyrite\Summary;

class FlightGroup extends FlightGroupBase implements Summary
{
    public function hasMothership()
    {
        return $this->ArriveViaMothership === true || $this->AlternateArriveViaMothership === true;
    }

    public function getMothershipFG()
    {
        return $this->TIE->FlightGroups[$this->ArrivalMothership];
    }

    public function __toString()
    {
        $waves = $this->NumberOfWaves === 0 ? '' : ($this->NumberOfWaves + 1) . 'x';
        $parts = [
            $this->getIFFLabel(),
            "{$waves}{$this->NumberOfCraft}",
            $this->getCraftTypeLabel(),
            $this->Name
        ];

        return implode(" ", $parts);
    }

    public function hasMultipleWaves()
    {
        return $this->NumberOfWaves > 0;
    }

    public function getIFFLabel(){
    	return $this->TIE->lookupIFF($this->Iff);
	}

	public function summaryHash(){
		$waves = $this->NumberOfWaves === 0 ? '' : ($this->NumberOfWaves + 1) . 'x';
		$diff = $this->getArrivalDifficultyLabel();
		return [
			'IFF' => $this->getIFFLabel(),
			'Craft' => "{$waves}{$this->NumberOfCraft}",
			'Type' => $this->getCraftTypeLabel(),
			'Name' => $this->printChar($this->Name),
			'Difficulty' => $diff
		];
	}
}
