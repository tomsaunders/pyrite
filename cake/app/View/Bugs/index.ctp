<div class="bugs index">
	<h2><?php echo __('Bugs'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('battle_id'); ?></th>
			<th><?php echo $this->Paginator->sort('reporter'); ?></th>
			<th><?php echo $this->Paginator->sort('date'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($bugs as $bug): ?>
	<tr>
		<td><?php echo h($bug['Bug']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($bug['Battle']['name'], array('controller' => 'battles', 'action' => 'view', $bug['Battle']['id'])); ?>
		</td>
		<td><?php echo h($bug['Bug']['reporter']); ?>&nbsp;</td>
		<td><?php echo h($bug['Bug']['date']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $bug['Bug']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $bug['Bug']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $bug['Bug']['id']), null, __('Are you sure you want to delete # %s?', $bug['Bug']['id'])); ?>
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
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Bug'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Battles'), array('controller' => 'battles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Battle'), array('controller' => 'battles', 'action' => 'add')); ?> </li>
	</ul>
</div>
