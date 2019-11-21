<?php
header("Access-Control-Allow-Origin: *");
include '../bootstrap.php';

$resource = "../../tie/RESOURCE/";
$file = strtoupper($_GET['name']);

if (file_exists($resource . $file)) {
    echo file_get_contents($resource . $file);
}
