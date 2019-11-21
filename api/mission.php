<?php
header("Access-Control-Allow-Origin: *");
include '../bootstrap.php';

$mission = "../../tie/MISSION/";
$file = strtoupper($_GET['name'] . '.tie');

if (file_exists($mission . $file)) {
    echo file_get_contents($mission . $file);
} elseif (file_exists("../../battles/TIE/free/" . $file)) {
    echo file_get_contents("../../battles/TIE/free/" . $file);
}
