<div class="notes form">
<?php echo $this->Form->create('Note'); ?>
	<fieldset>
		<legend><?php echo __('Edit Note'); ?></legend>
	<?php
		echo $this->Form->input('battle_id');
		echo $this->Form->input('from');
		echo $this->Form->input('notes');
		echo $this->Form->input('minutes');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Note.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Note.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Notes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Battles'), array('controller' => 'battles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Battle'), array('controller' => 'battles', 'action' => 'add')); ?> </li>
	</ul>
</div>
