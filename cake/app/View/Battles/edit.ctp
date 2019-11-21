<div class="battles form">
<?php echo $this->Form->create('Battle'); ?>
	<fieldset>
		<legend><?php echo __('Edit Battle'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('type');
		echo $this->Form->input('num');
		echo $this->Form->input('name');
		echo $this->Form->input('missions');
		echo $this->Form->input('rating');
		echo $this->Form->input('flown');
		echo $this->Form->input('gotzip');
		echo $this->Form->input('gotinfo');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Battle.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Battle.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Battles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Bugs'), array('controller' => 'bugs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bug'), array('controller' => 'bugs', 'action' => 'add')); ?> </li>
	</ul>
</div>
