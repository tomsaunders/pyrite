<?php
set_error_handler(function ($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
});

function pyriteLoader($class)
{
	$ds = DIRECTORY_SEPARATOR;
    $rootDir = dirname(dirname(__FILE__)) . $ds;
    $path = [$rootDir];

    // Pyrite\TIE\MissionBase
    $class = str_replace('Pyrite\\', '', $class); //strip project name;
    // TIE\MissionBase
    $bits = explode('\\', $class);
    if (count($bits) === 2) {
        list($platform, $class) = $bits;

        $path[] = $platform . $ds;
        if (strpos($class, 'Base')) {
            $path[] = 'gen' . $ds;
        }
    }

    $path[] = $class . '.php';
    $path = implode('', $path);
    require_once $path;
}
spl_autoload_register('pyriteLoader');

echo "<pre>";

$dir = "../../tie/";
$LFDs = [];
foreach (scandir($dir) as $d){
	if (is_dir($dir . $d) && strlen($d) > 2){
		foreach (scandir($dir . $d) as $f){
			if (strpos($f, '.LFD') !== FALSE){
				$LFDs[] = $d . '/' . $f;
			}
		}
	}
}
$out = [];
foreach ($LFDs as $fp){
	$str = file_get_contents($dir . $fp);
	$out[$fp] = Pyrite\LFD\LFD::fromHex($str);
}

//$beef = '../scratch/TIETC201/Battle1.lfd';
//$mark = '../scratch/TIEF275/BATTLE1.LFD';
//$lfd = new \Pyrite\LFD\BattleLFD(file_get_contents($beef)); // TODO dies on $mark now
//$image = $lfd->MapDelt->draw();
//ob_start();
//imagejpeg( $image );
//$data = ob_get_contents();
//ob_end_clean();
//
//$out[] = $lfd;
//
print_r($out);
echo "</pre>";

//$format = 'jpg';
//$data = base64_encode( $data );
//echo "<img src='data:image/$format;base64,$data'>";
