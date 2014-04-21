<?php
include('bootstrap.php');
$start = 0;
if (isset($_GET['start'])) $start = (int)$_GET['start'];
$i = 0;
$first;
$second;
foreach ($desc as $tie => $info){
    if ($i > ($start + 1)) break;
    if ($i === $start) $first = array($tie, $info);
    if ($i === ($start + 1)) $second = array($tie, $info);
    $i++;
}
if ($start > 0){
    echo "<a href='index.php?start=" . ($start-1) . "'>Previous</a> || ";
}
echo "<a href='index.php?start=" . ($start+1) . "'>Next</a>";

echo "<div style='float:left; width: 45%;'><pre>";
echo $first[0] . " - " . $first[1] . "\n";
$TIE = new TIE('hexing/' . $first[0]);
print_r($TIE->printDump());
echo "</pre></div>";
echo "<div style='float:right; width: 45%;'><pre>";
echo $second[0] . " - " . $second[1] . "\n";
$TIE = new TIE('hexing/' . $second[0]);
print_r($TIE->printDump());
echo "</pre></div>";
