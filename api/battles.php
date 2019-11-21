<?php
header("Access-Control-Allow-Origin: *");
include('../bootstrap.php');

$resource = "../../tie/RESOURCE";
$mission = "../../tie/MISSION";

$out = [];
for ($i = 1; $i <= 8; $i++) {
	$lfd = new \Pyrite\LFD\BattleLFD(file_get_contents("{$resource}/BATTLE${i}.LFD"));
	$title = $lfd->BattleText->TitleBattle1;
	$out[$title] = $lfd->BattleText->MissionFilenames;
}
echo json_encode($out, JSON_PRETTY_PRINT);

