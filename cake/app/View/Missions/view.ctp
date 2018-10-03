<div class="missions view">
<h2><?php echo __('Mission'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Battle'); ?></dt>
		<dd>
			<?php echo $this->Html->link($mission['Battle']['name'], array('controller' => 'battles', 'action' => 'view', $mission['Battle']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Position'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['position']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Filename'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['filename']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Highscore'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['hs_total']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Hs Name'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['hs_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Hs Pin'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['hs_pin']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Complexity'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['complexity']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Potentialscore'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['potentialscore']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Playercraft'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['playercraft']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Warheads'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['warheads']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Primary Goals'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['primary_goals']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Secondary Goals'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['secondary_goals']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Bonus Goals'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['bonus_goals']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reinforcements'); ?></dt>
		<dd>
			<?php echo h($mission['Mission']['reinforcements']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Mission'), array('action' => 'edit', $mission['Mission']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Mission'), array('action' => 'delete', $mission['Mission']['id']), null, __('Are you sure you want to delete # %s?', $mission['Mission']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Missions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mission'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Battles'), array('controller' => 'battles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Battle'), array('controller' => 'battles', 'action' => 'add')); ?> </li>
	</ul>
</div>
