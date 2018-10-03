<div class="battles">
	<h2><?php echo __('Battles'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th width="10%"><div id=""></div></th>
			<th width="10%">ID</th>
			<th width="70%">Name</th>
			<th width="10%">Missions</th>
<!--			<th>Complexity</th>-->
<!--            <th>Weighting</th>-->
<!--			<th>Bugs</th>-->
			<th class="actions" width="10%"></th>
	</tr>
    <?php
    $total = 0;
    $time = 0;
	$b = 0;
    ?>
	<?php foreach ($battles as $battle): ?>
        <?php
            //if ($battle['Battle']['type'] !== 'TIE-TC' || $battle['Battle']['num'] < 49) continue;
            $total += $battle['Battle']['missions'];
            $id = $battle['Battle']['type'] . $battle['Battle']['num'];

        ?>
	<tr class="battle <?php echo $id; ?>">
		<td><?php echo ++$b; ?>&nbsp;</td>
		<td><?php echo h($id); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['name']); ?>&nbsp;</td>
		<td><?php echo h($battle['Battle']['missions']); ?>&nbsp;</td>
<!--		<td>--><?php //echo h($battle['Battle']['complexity']); ?><!--&nbsp;</td>-->
<!--        <td>--><?php //echo h($battle['weighting']); ?><!--</td>-->
<!--		<td>--><?php //echo h($battle['Battle']['bugs']); ?><!--&nbsp;</td>-->
		<td class="actions"><?php
//			echo $this->Html->link(__('View'), array('action' => 'view', $battle['Battle']['id']));
            $view = 'http://tc.emperorshammer.org/download.php?id=' . $battle['Battle']['id'] . '&type=info';
            echo $this->Html->link(__('Battle Center'), $view);
            list($plat,$sg) = explode('-', $battle['Battle']['type']);
            $id = $plat . $sg . $battle['Battle']['num'];
            $ehm = "http://tc.emperorshammer.org/downloads/battles/$plat/$sg/$id.ehm";
            echo $this->Html->link(__('EHM'), $ehm);
            ?>
		</td>
	</tr>
        <?php foreach ($battle['Note'] as $note): ?>
        <tr class="note">
            <td> </td>
            <td colspan="1"><b><?php echo $note['from']; ?></b><br /><?php echo $note['notes']; ?></td>
            <td><?php if($note['minutes']) {
                    echo $note['minutes'] . ' minutes';
                    $time += $note['minutes'];
                } ?></td>
            <td> </td>
        </tr>
        <?php endforeach; ?>
<?php endforeach; ?>
    <tr>
        <th colspan="3">Total </th>
        <th id="total"><?php echo $total; ?></th>
        <th colspan="1"><?php if ($time) echo $time . ' minutes'; ?></th>
    </tr>
	</table>
</div>
<script>
    $('tr.battle').click(function(e){
        var $tr = $(e.currentTarget).next();
        while ($tr.is('.note')){
            $tr = $tr.show().next();
        }
    });

    //***************************
    //***************************
    //***************************
    //ENTER YOUR PIN
    var pin = 9999;
    //PIN GOES THERE ^^^
    //***************************
    //***************************
    //***************************

    $.get('http://rtf.tsaunders.net/transactions/view/' + pin, {}, function(data, status, xhr){
		var flown = 0;
        $(data).find('tr.SP').each(function(i,e){
            var id = $(e).data('name');
            id = id.replace(' #', '');
            var $tr = $('tr.battle.' + id);
            $tr.addClass('flown').addClass('yoda');
			if ($tr.length) flown += parseInt($tr.find('td:nth-child(3)').text().trim(),10);
        });
		$('th#total').text(flown + ' / <?php echo $total; ?>');
    });
</script>