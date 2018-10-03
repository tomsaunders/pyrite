<div class="missions form">
<?php echo $this->Form->create('Mission'); ?>
	<fieldset>
		<legend><?php echo __('Add Mission'); ?></legend>
	<?php
		echo $this->Form->input('battle_id');
		echo $this->Form->input('position');
		echo $this->Form->input('filename');
		echo $this->Form->input('highscore');
		echo $this->Form->input('hs_name');
		echo $this->Form->input('hs_pin');
		echo $this->Form->input('complexity');
		echo $this->Form->input('potentialscore');
		echo $this->Form->input('playercraft');
		echo $this->Form->input('warheads');
		echo $this->Form->input('primary_goals');
		echo $this->Form->input('secondary_goals');
		echo $this->Form->input('bonus_goals');
		echo $this->Form->input('reinforcements');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Missions'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Battles'), array('controller' => 'battles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Battle'), array('controller' => 'battles', 'action' => 'add')); ?> </li>
	</ul>
</div>
