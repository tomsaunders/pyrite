<?php
include('bootstrap.php');
include('TIEMissionReport.php');

$missionsDir = 'missions/';
$battles = scandir($missionsDir);
$missions = array();
foreach ($battles as $battle){
    if (strlen($battle) < 3) continue;
    $ties = scandir($missionsDir . $battle);
    foreach ($ties as $tie){
        if (strlen($tie) < 3) continue;
        $missions[$tie] = new TIE($missionsDir . $battle . '/' . $tie);
    }
}

$report = array();
foreach ($missions as $name => $TIE){
    $report[$name] = (new TIEMissionReport($TIE))->report;
}
?>
<pre>
    <?php print_r($report); ?>
</pre>