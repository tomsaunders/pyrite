<pre>
<?php
include('bootstrap.php');
$missions = array(
    'XWIW8/DEFECT.XWI',
    'XWIW8/ID-RECON.XWI',
    'XWIW8/WXRGARD1.XWI',
    'XWIW8/WXPROT2.XWI',
    'XWIW8/ATTACKXY.XWI'
);
$print = array();
foreach ($missions as $mission){
    $XW = new Pyrite\XWING\Mission('XWING/' . $mission);
    $sk = new Pyrite\XWING\ScoreKeeper($XW);
    $print[$mission] = $sk->printDump();
}
print_r($print);
?>
</pre>