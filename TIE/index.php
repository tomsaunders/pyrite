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
$out = [];

$lfd = new \Pyrite\TIE\BattleLfd(file_get_contents('../TIEF275/BATTLE1.LFD'));

$out[] = $lfd;

print_r($out);
echo "</pre>";
