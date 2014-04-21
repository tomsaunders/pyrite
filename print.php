<pre>
<?php
include('bootstrap.php');
$TIE = new Pyrite\TIE\Mission('EHM/tm3test/convertedTM3test.tie');
print_r($TIE->printDump());
?>
</pre>