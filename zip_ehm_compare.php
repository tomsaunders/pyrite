<?php
ini_set('memory_limit','4G');
require_once 'bootstrap.php';
require_once 'www/bootstrap.php';

$battlePath = '../battles/';
$tieTCDir = "{$battlePath}TIE/TC";

$b = "$tieTCDir/TIETC28";
$z = "$b.zip";
$e = "$b.ehm";

function scandirPath($path) {
	$bits = array_filter(scandir($path), 'realD');
	return array_map(function($p) use ($path) {
		$p = $path . $p;
		return is_dir($p) ? $p . DIRECTORY_SEPARATOR : $p;
	}, $bits);
}
function realD($d) {
	return $d !== '.' && $d !== '..';
}
function getZips($f) {
	return substr($f, -4, 4) === '.zip';
}
$out = [];
$plats = array_filter(scandirPath($battlePath), 'is_dir');
$plats = [$battlePath . 'TIE/']; // havent done the battle / packager for all platforms yet

foreach ($plats as $plat){
	$subgs = array_filter(scandirPath($plat), 'is_dir');
	$subgs = [$plat . 'TC/'];
	foreach ($subgs as $subg) {
		$zips = array_filter(scandirPath($subg), 'getZips');
		foreach ($zips as $zip) {
			$ehm = str_replace('.zip', '.ehm', $zip);
			if (!file_exists($ehm)){
//				$out[] = "$ehm does not exist but $zip does";
				// just SWGB and XW
			} else {
				set_time_limit(90);
				$ebat = \Pyrite\EHBL\Battle::fromEHM($ehm);
				$epak = \Pyrite\EHBL\Packager::fromBattle($ebat);
				$zbat = \Pyrite\EHBL\Battle::fromZip($zip);
				$zpak = \Pyrite\EHBL\Packager::fromBattle($zbat);
				$comparison = \Pyrite\EHBL\Validator::compare($zpak, $epak);
				if (count($comparison)){
					echo "\n$zip\n";
					print_r($comparison);
				}
				$out[$zip] = $comparison;
			}
		}
	}
}

//$content = bs_pre($out);
//
//echo bs_header();
//echo bs_navbar("Pyrite", "Xtractr", []);
//echo bs_two_column([], $content);
//echo bs_footer();

