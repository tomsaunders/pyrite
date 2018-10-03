<div class="bugs view">
<h2><?php echo __('Bug'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($bug['Bug']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Battle'); ?></dt>
		<dd>
			<?php echo $this->Html->link($bug['Battle']['name'], array('controller' => 'battles', 'action' => 'view', $bug['Battle']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reporter'); ?></dt>
		<dd>
			<?php echo h($bug['Bug']['reporter']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($bug['Bug']['date']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Bug'), array('action' => 'edit', $bug['Bug']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Bug'), array('action' => 'delete', $bug['Bug']['id']), null, __('Are you sure you want to delete # %s?', $bug['Bug']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Bugs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bug'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Battles'), array('controller' => 'battles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Battle'), array('controller' => 'battles', 'action' => 'add')); ?> </li>
	</ul>
</div>
