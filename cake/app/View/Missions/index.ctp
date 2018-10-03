<div class="missions">
	<h2><?php echo __('Missions'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('battle_id'); ?></th>
			<th><?php echo $this->Paginator->sort('position', '#'); ?></th>
			<th><?php echo $this->Paginator->sort('filename'); ?></th>
			<th><?php echo $this->Paginator->sort('hs_total'); ?></th>
			<th><?php echo $this->Paginator->sort('complexity'); ?></th>
			<th><?php echo $this->Paginator->sort('potentialscore', 'Max'); ?></th>
			<th><?php echo $this->Paginator->sort('playercraft', 'Ship'); ?></th>
			<th><?php echo $this->Paginator->sort('warheads'); ?></th>
			<th><?php echo $this->Paginator->sort('primary_goals'); ?></th>
			<th><?php echo $this->Paginator->sort('secondary_goals'); ?></th>
			<th><?php echo $this->Paginator->sort('bonus_goals'); ?></th>
			<th><?php echo $this->Paginator->sort('reinforcements', 'Reinf'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($missions as $mission): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($mission['Battle']['name'], array('controller' => 'battles', 'action' => 'view', $mission['Battle']['id'])); ?>
		</td>
		<td><?php echo h($mission['Mission']['position']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['filename']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['hs_total']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['complexity']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['potentialscore']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['playercraft']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['warheads']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['primary_goals']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['secondary_goals']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['bonus_goals']); ?>&nbsp;</td>
		<td><?php echo h($mission['Mission']['reinforcements']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $mission['Mission']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $mission['Mission']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $mission['Mission']['id']), null, __('Are you sure you want to delete # %s?', $mission['Mission']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
