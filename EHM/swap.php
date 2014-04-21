
<?php
$match = array();

$zipTIE = file_get_contents('TIEF286/Rookie.tie');
$ehmTIE = file_get_contents('TIEF286ehm/Rookie.tie');

$print = array(
);

for ($c = 0; $c < strlen($zipTIE); $c++){
    $z = ord($zipTIE[$c]);
    $e = ord($ehmTIE[$c]);

    if (!isset($match[$z])){
        $match[$z] = $e;
    } else {
        if ($match[$z] !== $e){
            $print[] = array(
                'position' => $c,
                'zip' => $z,
                'ehm' => $e,
                'normally' => $match[$z]
            );
        }
    }
}

$sums = array(
    175 => array(),
    207 => array(),
    303 => array(),
    335 => array()
);

foreach ($match as $z => &$e){
    $sum = $z + $e;
    $sums[$sum][$z] = $e;
    $sums[$sum]['.'.dechex($z)] = dechex($e);
//    $e = array(
//        'e' => $e,
//        'sum' => $z + $e
//    );
}

$print['sums'] = $sums;
$print['matches'] = $match;

?>
<pre>
    <?php print_r($print); ?>
</pre>