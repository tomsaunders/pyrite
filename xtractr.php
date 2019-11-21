<?php
require_once 'bootstrap.php';
require_once 'www/bootstrap.php';

$battlePath = '../battles/';
$xwaD = "{$battlePath}XWA/IW/";

$zips = array_filter(scandir($xwaD), function($f) {
	return strpos($f, ".zip") !== FALSE;
});
$out = [];
foreach ($zips as $zipFile){
	$zipPath = "$xwaD{$zipFile}";
	$battle = \Pyrite\EHBL\Battle::fromZip($zipPath);
	$package = \Pyrite\EHBL\Packager::fromBattle($battle);
	$res = \Pyrite\EHBL\Validator::fromPackage($package);
	$out[$zipFile] = $res;
}

$content = bs_pre($out);

echo bs_header();
echo bs_navbar("Pyrite", "Xtractr", []);
echo bs_two_column([], $content);
echo bs_footer();

