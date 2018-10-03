<pre><?php
	include('../bootstrap.php');
	$path = '../tests/XvTF165/Khad.tie';
	$path = '../tests/XvTF177/Act_1.tie';

	for ($i = 1; $i <= 4; $i++) {
		$path = "../tests/BoPTC27/Wrath$i.tie";
		$XvT = new \Pyrite\XvT\Mission(file_get_contents($path));
//		print_r($XvT->printDump());

		$sk = new \Pyrite\XvT\ScoreKeeper($XvT, 'Medium');
		print_r($sk->printDump());
	}
?>
</pre>