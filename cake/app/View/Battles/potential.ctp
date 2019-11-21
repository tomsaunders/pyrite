<div class="battles">
	<h2><?php echo __('Battles'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
            <th>ID</th>
            <th>Name</th>
            <th>Missions</th>
            <th>Mission Diff Avg</th>
            <th>HS total diff</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($battles as $battle): ?>
	<tr>
		<td><?php echo h($battle['Battle']['type'] . $battle['Battle']['num']); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['name']); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['missions']); ?>&nbsp;</td>
		<td><?php echo h(round($battle['Battle']['mdiff'])); ?>&nbsp;</td>
        <td><?php echo h($battle['Battle']['bdiff']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $battle['Battle']['id'])); ?>
            <?php echo $this->Html->link(__('Update'), array('action' => 'update', $battle['Battle']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
