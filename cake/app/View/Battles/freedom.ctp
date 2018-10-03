<table>
    <tr><th>Name</th><th>Count</th><th>Details</th></tr>
<?php
function getDetails($data)
{
    $agg = [];
    foreach ($data as $fg) {
        $fg = str_replace("-", "_", $fg);
        if (!isset($agg[$fg])) {
            $agg[$fg] = 0;
        }
        $agg[$fg]++;
    }
    arsort($agg);
    $ret = "";
    foreach ($agg as $fg => $c) {
        $ret .= "$fg : $c<br />";
    }

    return $ret;
}

foreach ($names as $name => $data) {
    $c = count($data);
    $details = getDetails($data);
    echo "<tr><td>$name</td><td>$c</td><td>$details</td></tr>";
}
?>
</table>