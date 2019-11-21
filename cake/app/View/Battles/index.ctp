<div class="battles">
	<h2><?php echo __('Battles'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id', '#'); ?></th>
			<th>ID</th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('missions'); ?></th>
			<th><?php echo $this->Paginator->sort('rating'); ?></th>
			<th><?php echo $this->Paginator->sort('flown'); ?></th>
			<th><?php echo $this->Paginator->sort('hs_total'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($battles as $battle): ?>
	<tr>
		<td><?php echo h($battle['Battle']['id']); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['type'] . $battle['Battle']['num']); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['name']); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['missions']); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['rating']); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['flown']); ?>&nbsp;</td>
        <td><?php echo h($battle['Battle']['hs_total']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $battle['Battle']['id'])); ?>
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
