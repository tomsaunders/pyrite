<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Pyrite-<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

        if (isset($export)){
            echo "<style>";
            echo file_get_contents(WWW_ROOT . 'css' . DS . 'cake.generic.css');
            echo "</style>";
        } else {
            echo $this->Html->css('cake.generic');
        }

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Pyrite: Pickled Yoda reads TIE files</h1>
		</div>
		<div id="content">

			<?php
			if (isset($print)){
				echo "<pre>";
				print_r($print);
				echo "</pre>";
			}
			?>

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			GN Pickled Yoda
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
