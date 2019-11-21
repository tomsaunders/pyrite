<?php

require('../bootstrap.php');

$dir = dirname(dirname(__FILE__)) . '/';
$builder = new Pyrite\Build\Builder($dir);
$ts = new Pyrite\Build\TSBuilder($dir, $dir . 'editor/pyrite/src/model/');

echo "<pre>";
//$out = $ts->test();
//$out = $builder->run(['TIE']);
$out = $ts->run(['TIE']);
echo "</pre>";
