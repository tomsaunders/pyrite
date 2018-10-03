<div class="notes view">
<h2><?php echo __('Note'); ?></h2>
	<dl>
		<dt><?php echo __('Battle'); ?></dt>
		<dd>
			<?php echo $this->Html->link($note['Battle']['name'], array('controller' => 'battles', 'action' => 'view', $note['Battle']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('From'); ?></dt>
		<dd>
			<?php echo h($note['Note']['from']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Notes'); ?></dt>
		<dd>
			<?php echo h($note['Note']['notes']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Minutes'); ?></dt>
		<dd>
			<?php echo h($note['Note']['minutes']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Note'), array('action' => 'edit', $note['Note']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Note'), array('action' => 'delete', $note['Note']['id']), null, __('Are you sure you want to delete # %s?', $note['Note']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Notes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Note'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Battles'), array('controller' => 'battles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Battle'), array('controller' => 'battles', 'action' => 'add')); ?> </li>
	</ul>
</div>
