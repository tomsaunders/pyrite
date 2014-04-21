<pre>
<?php
	include('../bootstrap.php');
	$decoder = new \Pyrite\EHM\Decoder('tm3test.ehm');
	print_r($decoder->debugOutput());
?>
</pre>