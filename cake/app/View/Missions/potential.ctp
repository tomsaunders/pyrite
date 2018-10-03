<div class="missions">
	<h2><?php echo __('Missions'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>Battle</th>
			<th>#</th>
			<th>HS</th>
			<th>Max</th>
            <th>Diff</th>
            <th>Pilot</th>
            <th>Craft</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($missions as $mission): ?>
	<tr>
		<td><?php
            $b = $mission['Battle'];
            $bat = str_replace(array('-','free'), array('','F'), $b['type'] . $b['num']);
            echo $this->Html->link($bat, array('controller' => 'battles', 'action' => 'view', $mission['Battle']['id']));
            ?></td>
		<td><?php echo h($mission['Mission']['position']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['hs_total']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['potentialscore']); ?>&nbsp;</td>
        <td><?php echo h($mission['Mission']['ScoreDiff']); ?>&nbsp;</td>
        <td><?php echo $this->Html->link($mission['Mission']['hs_name'], array('action' => 'pilot', $mission['Mission']['hs_pin'])); ?></td>
        <td><?php echo implode(' - ', array($mission['Mission']['playercraft'], $mission['Mission']['warheads'])); ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view',   $mission['Mission']['id'])); ?>
            <?php echo $this->Html->link(__('Update'), array('controller' => 'battles', 'action' => 'update',   $mission['Mission']['battle_id'])); ?>
			<?php echo $this->Html->link(__('Score'), array('action' => 'score', $mission['Mission']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
