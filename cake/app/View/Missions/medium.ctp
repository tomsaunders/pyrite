<div class="missions">
<h2><?php echo $mission['Battle']['type'] . $mission['Battle']['num']; ?></h2>
	<dl>
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
			<?php echo $this->Html->link($mission['Mission']['hs_pin'], array('action' => 'pilot', $mission['Mission']['hs_pin'])); ?>
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
        <dt><?php echo __('Next'); ?></dt>
        <dd>
            <?php if ($next) echo $this->Html->Link('Next', array('action' => 'medium', $next)); ?>
            &nbsp;
        </dd>
		<dt><?php echo __('Hard'); ?></dt>
		<dd>
			<?php echo $this->Html->Link('Hard', array('action' => 'score', $mission['Mission']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
    <pre>
        <?php print_r($score); ?>
    </pre>
</div>