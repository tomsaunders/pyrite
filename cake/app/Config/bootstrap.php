<?php
// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

$pq = DS . 'phpQuery';
require_once(VENDORS . $pq . $pq . $pq . '.php');
require_once(ROOT . DS . '..' . DS . 'bootstrap.php');

define('BATTLES_PATH', WWW_ROOT . 'files' . DS . 'battles' . DS);