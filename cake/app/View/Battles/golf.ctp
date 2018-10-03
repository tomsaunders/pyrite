<?php
foreach ($battles as $golf) {
    $allyScore = $golf['points']['ImpCap'] + $golf['points']['ImpFighter'];

?>
    <h2><?php echo $golf['mission']; ?></h2>
    <p><?php echo $golf['fgs']['Player']; ?></p>
    <p><?php echo $golf['reinf'] ? "<b>Reinforcements Available</b>" : "No reinforcements"; ?></p>
    <table>
        <tr><th>Allies</th><th>Enemies</th><th>Goals</th></tr>
        <tr>
            <td>
                <?php echo implode("<br />", $golf['fgs']['ImpCap']); ?>
                <?php echo implode("<br />", $golf['fgs']['ImpFighter']); ?>
            </td>
            <td>
                <?php echo implode("<br />", $golf['fgs']['Hostile']); ?>
            </td>
            <td>
                <?php echo implode("<br />", $golf['goals']); ?>
            </td>
        </tr>
        <tr><th><?php echo $allyScore; ?></th><th><?php echo $golf['points']['Hostile']; ?></th><th></th></tr>
    </table>
<?php
}
