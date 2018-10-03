<pre>
<?php
include('bootstrap.php');
$path = 'cake/app/webroot/files/battles/';
$missions = array(
);
$print = array();
$types = array(
    'TC',
    'IW',
    'DB',
    'ID',
    'CAB',
    'FCHG',
    'BHG',
    'F'
);
$limit = 300;
foreach ($types as $type){
	for ($i = 1; $i <= $limit; $i++){
		$battle = 'TIE' . $type . $i . '/';
		if (file_exists($path . $battle)){
			foreach (scandir($path . $battle) as $filename){
				$bits = explode('.', $filename);
				if (strtolower(array_pop($bits)) === 'tie'){
					$missions[] = $battle . $filename;
				}
			}
		} else {
            continue 2;
        }
	}
}
$start = microtime(TRUE);
ini_set('memory_limit','512M');
foreach ($missions as $mission){
    set_time_limit(30);
	$TIE = new \Pyrite\TIE\Mission($path . $mission);
	$gk = new \Pyrite\TIE\GoalKeeper($TIE);
    if ($gk->HR()){
        $print[$mission] = $gk->printDump();
    }
}
$end = microtime(TRUE);
$print[] = count($print) . ' missions with heavy rockets';
$print[] = count($missions) . ' processed in ' . (($end-$start)) . ' seconds';
print_r($print);
?>
</pre>