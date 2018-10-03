<div class="missions">
	<h2><?php echo __('Missions'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>    <th>#</th>
			<th>Battle</th>
			<th>m</th>
			<th>Complexity</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($missions as $i => $mission): ?>
	<tr>
        <td><?php echo $i; ?></td>
		<td><?php
            $b = $mission['Battle'];
            $bat = str_replace(array('-','free'), array('','F'), $b['type'] . $b['num']);
            echo $this->Html->link($bat, array('controller' => 'battles', 'action' => 'view', $mission['Battle']['id']));
            ?></td>
		<td><?php echo h($mission['Mission']['position']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['complexity']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view',   $mission['Mission']['id'])); ?>
			<?php echo $this->Html->link(__('Score'), array('action' => 'score', $mission['Mission']['id'])); ?>
            <?php echo $this->Html->link(__('Complex'), array('action' => 'complex', $mission['Mission']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
