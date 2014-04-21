<?php
include('../tests/bootstrap.php');
$mission = 1;
if (isset($_GET['mission'])) $mission = (int)$_GET['mission'];
if ($mission < 10) $mission = '0' . $mission;
$mission = 'Beef' . $mission . '.tie';

if ($mission > 0){
    echo "<a href='index.php?start=" . ($mission-1) . "'>Previous</a> || ";
}
echo "<a href='index.php?start=" . ($mission+1) . "'>Next</a>";

$TIE = new TIE($mission);

$print = array(
    'Primary' => array(),
    'Secondary' => array(),
    'Bonus' => array()
);

$noGoal = array(
    'PrimaryWhat' => 'None',
    'PrimaryWho' => '100%',
    'SecondrWhat' => 'None',
    'SecondrWho' => '100%',
    'SecretWhat' => 'None',
    'SecretWho' => '100%',
    'BonusWhat' => 'None',
    'BonusWho' => '100%',
    'BonusPts' => '0'
);

foreach ($TIE->flightGroups as $fg) {
    $win = $fg['Win'];
    $name = $fg['General']['ShipType']->Name . ' ' . $fg['General']['Name'];
    if ($win['PrimaryWhat'] !== 'None') $print['Primary'][] = "FG $name " . $win['PrimaryWho'] . $win['PrimaryWhat'];
    if ($win['SecondrWhat'] !== 'None') $print['Secondary'][] = "FG $name " . $win['SecondrWho'] . $win['SecondrWhat'];
    if ($win['BonusWhat'] !== 'None') $print['Bonus'][] = "FG $name " . $win['BonusWho'] . $win['BonusWhat'] . ' - ' . $win['BonusPts'];
}

foreach ($TIE->globalGoals as $gg){
    $print[] = $gg;
}


echo '<pre>';
print_r($print);
echo '</pre>';