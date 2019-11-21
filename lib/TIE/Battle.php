<?php

namespace Pyrite\TIE;

use Pyrite\EHBL\BattleType;
use Pyrite\EHBL\Platform;
use Pyrite\LFD\BattleLFD;

class Battle extends \Pyrite\EHBL\Battle {
	public function __construct($type = BattleType::UNKNOWN, $num = 0, $title = '', $folder = '', array $missionFiles = [], array $resourceFiles = []) {
		parent::__construct(Platform::TIE, $type, $num, $title, $folder, $missionFiles, $resourceFiles);
	}

	public static function fromFolder($type = BattleType::UNKNOWN, $num = 0, $folder = '', array $lfds = [], array $missionFiles = [], array $resourceFiles = []) {
		// TODO validation that LFDs has items
		// TOTO validation that LFD matches mission files etc
		// TODO handle multiple LFD battles
		$lfd = new BattleLFD(file_get_contents($folder . reset($lfds)));
		return new Battle($type, $num, $lfd->BattleText->TitleBattle1, $folder, $missionFiles, $resourceFiles);
	}
}