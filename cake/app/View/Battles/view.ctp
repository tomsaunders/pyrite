<div class="battles view">
<h2><?php echo __('Battle'); ?></h2>
	<dl>
		<dt><?php echo __('DB ID'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Num'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['num']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Missions'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['missions']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rating'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['rating']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Flown'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['flown']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gotzip'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['gotzip']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gotinfo'); ?></dt>
		<dd>
			<?php echo h($battle['Battle']['gotinfo']); ?>
			&nbsp;
		</dd>
        <dt><?php echo __('High Score'); ?></dt>
        <dd>
            <?php echo h($battle['Battle']['hs_total']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('High Score Pilot'); ?></dt>
        <dd>
            <?php echo h('#' . $battle['Battle']['hs_pin'] . ' ' . $battle['Battle']['hs_name']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Bugs'); ?></dt>
        <dd>
            <?php echo h(count($battle['Bug'])); ?>
            &nbsp;
        </dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(
                'Battle Center Page',
                'http://tc.emperorshammer.org/download.php?id=' . $battle['Battle']['id'] . '&type=hs'); ?> </li>
        <li><?php echo $this->Html->link('Add note', array('controller' => 'notes', 'action' => 'add', $battle['Battle']['id'])); ?></li>
        <li><?php echo $this->Html->link('Spam', array('action' => 'spam')); ?></li>
        <li><?php echo $this->Html->link('Update', array('action' => 'update', $battle['Battle']['id'])); ?></li>
	</ul>
</div>
<div class="related">
    <h3><?php echo __('Missions'); ?></h3>
    <?php if (!empty($battle['Mission'])): ?>
        <table cellpadding = "0" cellspacing = "0">
            <tr>
                <th>#</th>
                <th>Filename</th>
                <th>Score</th>
                <th>Max</th>
                <th>Diff</th>
                <th>Pilot</th>
                <th>Complexity</th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
            <?php
                $hsTotal = 0;
                $maxTotal = 0;
                $diffTotal = 0;
                foreach ($battle['Mission'] as $m):
                    $hsTotal += $m['hs_total'];
                    $maxTotal += $m['potentialscore'];
                    $diffTotal += $m['ScoreDiff'];
            ?>
                <tr>
                    <td><?php echo $m['position']; ?></td>
                    <td><?php echo $m['filename']; ?></td>
                    <td><?php echo $m['hs_total']; ?></td>
                    <td><?php echo $m['potentialscore']; ?></td>
                    <td><?php echo $m['ScoreDiff']; ?></td>
                    <td>#<?php echo $m['hs_pin'] . ' ' . $m['hs_name']; ?></td>
                    <td><?php echo $m['complexity']; ?></td>
                    <td><?php echo $this->Html->link('View', array('controller' => 'missions', 'action' => 'view', $m['id'])); ?>
                        <?php echo $this->Html->link('Score', array('controller' => 'missions', 'action' => 'score', $m['id'])); ?>
                        <?php echo $this->Html->link('Complex', array('controller' => 'missions', 'action' => 'complex', $m['id'])); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th></th>
                <th></th>
                <th><?php echo $hsTotal; ?></th>
                <th><?php echo $maxTotal; ?></th>
                <th><?php echo $diffTotal; ?></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </table>
    <?php endif; ?>
    <?php if (!empty($battle['Notes'])): ?>
        <h3><?php echo __('Notes'); ?></h3>
        <table cellpadding = "0" cellspacing = "0">
            <tr>
                <th>From</th>
                <th>Note</th>
                <th>Minutes</th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
            <?php foreach ($battle['Note'] as $m): ?>
                <tr>
                    <td><?php echo $m['from']; ?></td>
                    <td><?php echo $m['note']; ?></td>
                    <td><?php echo $m['minutes']; ?></td>
                    <td><?php echo $this->Html->link('Edit', array('controller' => 'notes', 'action' => 'edit', $m['id'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
