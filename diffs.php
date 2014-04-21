<html>
<head>
<style>
    table {
        padding: 0;
        border-spacing: 0;
        border-collapse: collapse;
    }
    tr {
        margin: 0;
        padding: 0;
    }
    td {
        width: 25px;
        text-align: center;
        height: 25px;
        margin: 0;
        padding: 0;
    }
    td:first-child, td:last-child{
        width: 150px;
        text-align: left;
    }
    td:nth-child(2), td:nth-child(3), td:nth-child(4), td:nth-child(6), td:nth-child(8), td:nth-child(12), td:nth-child(15) {
        background-color: cadetblue;
    }
</style>
</head>
<body>
<!--<table>-->
<pre>
<?php
include('bootstrap.php');

$dir = 'hexing';
$dir = 'MISSION';

$files = scandir($dir);
foreach ($files as $file){
//    echo "<tr>";
    if (!strpos(strtolower($file), '.tie')) continue;
    $tie = new TIE($dir . '/' . $file);
    if (count($tie->unknownSet)){
//        echo "<td>$file</td>";
        echo "$file\n";
        print_r($tie->unknownSet);
    }
//    Hex::render($tie->headerString, TRUE);
//    if (isset($desc[$file])) echo "<td> - " . $desc[$file] . '</td>';
//    echo '</tr>';
}
?>
    </pre>
<!--</table>-->
</body>
</html>