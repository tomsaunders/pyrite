<div class="bugs form">
<?php echo $this->Form->create('Bug'); ?>
	<fieldset>
		<legend><?php echo __('Edit Bug'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('battle_id');
		echo $this->Form->input('reporter');
		echo $this->Form->input('date');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Bug.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Bug.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Bugs'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Battles'), array('controller' => 'battles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Battle'), array('controller' => 'battles', 'action' => 'add')); ?> </li>
	</ul>
</div>
