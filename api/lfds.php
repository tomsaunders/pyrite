<?php
header("Access-Control-Allow-Origin: *");
include '../bootstrap.php';

$resource = "../../tie/RESOURCE";
$out = [];
foreach (scandir($resource) as $file) {
    if (strlen($file) > 2 && substr($file, -4, 4) === '.LFD') {
        try {
            $lfd = \Pyrite\LFD\LFD::fromHex(file_get_contents("{$resource}/{$file}"));
            $out[$file] = $lfd->__debugInfo();
        } catch (error $e) {
            $out[] = $e;
        }
    }
}
echo json_encode($out, JSON_PRETTY_PRINT);
?>
