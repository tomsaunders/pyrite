<?php
include('Hex.php');

$a = file_get_contents('TIETC34ehm/Battle.ehb');
$b = file_get_contents('TIETC30ehm/Battle.ehb');

//$free = file_get_contents('TIETC28ehm/Battle.ehb');
//$bat = file_get_contents('TIETC236ehm/Battle.ehb');

$print = array(
    Hex::hexToStr($a),
    Hex::hexToStr($b)
);

function ehb($hex){
    $ehb = array();
    $ehb['unk1'] = ord($hex[1]);
    $ehb['title'] = substr($hex, 2, 50);
    $ehb['#'] = ord($hex[53]);
    $ehb['unk2'] = ord($hex[54]);
    $ehb['missions'] = array();
    $remaining = substr($hex, 55);
    for ($m = 0; $m < $ehb['#']; $m++){
        $ehb['missions'][] = substr($remaining, 20 * $m, 20);
    }
    $remaining = substr($remaining, 21 * $ehb['#']);
    $ehb['rem'] = Hex::hexToStr($remaining);
    for ($r = 0; $r < strlen($remaining); $r++){
        $ehb['r' . $r] = ord($remaining[$r]);
    }

    return $ehb;
}

?>
<pre>
    <?php print_r($print); ?>
</pre>
<div style="width: 48%; float: left;">
    <pre>
        <?php print_r(ehb($a)); ?>
    </pre>
</div>
<div style="width: 48%; float: left;">
    <pre>
        <?php print_r(ehb($b)); ?>
    </pre>
</div>