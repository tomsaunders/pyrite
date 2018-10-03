<div class="battles form">
<?php echo $this->Form->create('Battle'); ?>
	<fieldset>
		<legend><?php echo __('Add Battle'); ?></legend>
	<?php
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

		<li><?php echo $this->Html->link(__('List Battles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Bugs'), array('controller' => 'bugs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bug'), array('controller' => 'bugs', 'action' => 'add')); ?> </li>
	</ul>
</div>
