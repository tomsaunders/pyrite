<?php

namespace Pyrite;

interface IFlightGroup {
	/**
	 * @param $level string difficulty
	 * @return bool whether this flight group is present in the provided difficulty level
	 */
	public function isInDifficultyLevel($level);

	/**
	 * Get potential maximum point value for this flight group
	 * takes IFF into account, as well as difficulty level
	 * @param $level string difficulty
	 * @return int
	 */
	public function pointValue($level);

	/** @return bool */
	public function isPlayerCraft();
}