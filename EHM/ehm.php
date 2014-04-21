<?php
	include(
		'Hex.php'
	);
	$zipTIE = file_get_contents('TIEF286/Rookie.tie');
	$ehmTIE = file_get_contents('TIEF286ehm/Rookie.tie');

//	$zipTIE = substr($zipTIE, 0, 512);
//	$ehmTIE = substr($ehmTIE, 0, 512);
	$ehmTIEfix = $ehmTIE;

	$rot = 175;
//	$rot = -81;

	$z = 0;
	$e = 0;
	$mismatch = array();
	for ($i = 0; $i < strlen($ehmTIEfix); $i++){
		$value 		= unpack('c', $ehmTIE[$i])[1];
		$decrypt 	= $rot ^ $value;
		$ehmTIEfix[$i] = pack('c', $decrypt);


		if ($zipTIE[$i] === $ehmTIEfix[$i]) {
			$z++;
		} else if ($value >= 0){
			$mismatch[sprintf("'%02X' %+4d", ord($zipTIE[$i]), unpack('c', $zipTIE[$i])[1])] =
				sprintf("'%02X' %+4d '%02X' %+4d", ord($ehmTIEfix[$i]), unpack('c', $ehmTIEfix[$i])[1], ord($ehmTIE[$i]), unpack('c', $ehmTIE[$i])[1]);
		}
		if ($ehmTIE[$i] === $ehmTIEfix[$i]) $e++;
	}

?>
<body style="font-size:10px;">
<pre>
	<?php echo Hex::hexToStr(file_get_contents('TIEF286ehm/Battle.ehb')) ?>
	<?php
	$print = array();
	foreach (array(24,25,26,27,28) as $k){
		$value = unpack('c', $ehmTIE[$k])[1];
		$print[] = implode(' , ', array(
			sprintf("'%02X'", ord($zipTIE[$k])),
			sprintf("%+4d", unpack('c', $zipTIE[$k])[1]),
			sprintf("'%02X'", ord($ehmTIE[$k])),
			sprintf("%+4d", unpack('c', $ehmTIE[$k])[1]),
			sprintf("'%02X'", ord($ehmTIEfix[$k])),
			sprintf("%+4d", unpack('c', $ehmTIEfix[$k])[1]),
			$rot,
			$value >= 0 ? '>=0' : '<0',
			$value >= 0 ? $value + $rot : (128 -$value + $rot) % 128,
			$rot & $value,
			$rot | $value,
			$rot ^ $value,
			ord($zipTIE[$k]) === ord($ehmTIEfix[$k]) ? 'MATCH' : 'DIFF'
		));
	}
//	$print[] = $mismatch;
	print_r($print);
	?>
</pre>
<div style="float:left; width: 30%;">
<pre>
	RAW ZIP
	<?php
	echo Hex::hexToStr($zipTIE);
	echo "\n";echo "\n";
	echo $z;
	?>
	/512
</pre>
</div>
<div style="float:left; width: 30%;">
<pre>
	MERGE ATTEMPT
	<?php
	echo Hex::hexToStr($ehmTIEfix);
	echo "\n";echo "\n";
	echo strlen($ehmTIEfix);
	?>
</pre>
</div>
<div style="float:left; width: 30%;">
<pre>
	RAW EHM
	<?php
	echo Hex::hexToStr($ehmTIE);
	echo "\n";echo "\n";
	echo $e;
	?>
	/512
</pre>
</div>
</body>