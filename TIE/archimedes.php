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

$dir = "../MISSION/";
$missions = array_filter(scandir($dir), function ($file) {
    return substr($file, -4, 4) === ".TIE";
});
//$missions = ["B1M1FM.TIE"];

echo "<pre style='white-space: pre-line;'>";
foreach ($missions as $mission) {
    $hex = file_get_contents("{$dir}{$mission}") . chr(255) . chr(255);
    $TIE = new \Pyrite\TIE\Mission($hex);

    $problems = ["IFF Rule $mission"];

    foreach ($TIE->FlightGroups as $flightGroup) {
        $flightGroup->TIE = $TIE;
        if ($flightGroup->hasMothership()) {
            $mother = $flightGroup->getMothershipFG($TIE);
            if ($mother->Iff !== $flightGroup->Iff) {
                $problems[] = "$flightGroup launches from $mother";
            }
        }
    }
    if (count($problems) > 1) {
        print_r($problems);
    }

    $messages = ["Messages $mission"];
    foreach ($TIE->Messages as $message) {
        $message->TIE = $TIE;

        $trigger = $message->Triggers[0];
        $trigger->TIE = $TIE;
        if ($trigger->TriggerAmount === 0 && $trigger->VariableType === 1 && $trigger->Condition === 1) {
            $fg = $TIE->FlightGroups[$trigger->Variable];
            if ($fg->hasMultipleWaves()) {
                $messages[] = [$message->Message, (string) $trigger];
            }
        }
    }
    if (count($messages) > 1) {
        print_r($messages);
    }
}
echo "</pre>";
