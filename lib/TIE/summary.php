<html>
<head>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
			integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
			crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
			integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
			crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		  integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
			integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
			crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://getbootstrap.com/docs/4.1/examples/dashboard/dashboard.css" />
	<style>
		.navbar-brand {
			text-align: center;
		}
	</style>
</head>
</html>
<body>
<?php
require('../bootstrap.php');

$bID = isset($_GET['battleID']) ? $_GET['battleID']: 176;
$file = isset($_GET['file']) ? $_GET['file'] : '';

$dir = "../../battles/TIE/tmp/TIETC28z/";
$files = [];
foreach (scandir($dir) as $f){
	if (strlen($f) > 2) $files[] = $f;
	if (!$file && substr(strtolower($f), -4, 4) === '.txt'){
		$file = $f;
	}
}

$path = "{$dir}{$file}";
$contents = file_get_contents($path);
$ext = substr(strtolower($file), -4, 4);
if ($ext === '.txt'){
	$menu = [
			'Contents' => $contents
	];
} else if ($ext === '.tie') {
	$miss = new \Pyrite\TIE\Mission($contents . chr(255) . chr(255));

	$menu = [
		'Flight Groups'          => summarise($miss, 'FlightGroups'),
		'Messages'               => summarise($miss, 'Messages'),
		'Global Goals'           => summarise($miss, 'GlobalGoals'),
		'Briefing'               => summarise($miss, 'Events', $miss->Briefing),
		'Pre Mission Questions'  => summarise($miss, 'PreMissionQuestions'),
		'Post Mission Questions' => summarise($miss, 'PostMissionQuestions'),
	];
} else {
	$menu = ['Dunno!' => "Not sure how to parse $file"];
}

/**
 * @param \Pyrite\TIE\Mission $miss
 * @param string              $prop
 * @param                     $parent
 * @return array
 */
function summarise($miss, $prop, $parent = NULL) {
	$parent = $parent ?: $miss;
	/** @var \Pyrite\PyriteBase[] */
	$var = $parent->{$prop};
	return array_map(function ($p) use ($miss) {
		$p->TIE = $miss;
		return $p->summaryHash();
	}, $var);
}

function id($section) {
	return str_replace(' ', '', $section);
}

function li($section) {
	$id = id($section);
	$js = " data-section'$id'";
	return <<<LI
 		<li class="nav-item">
			<a class="nav-link" href="#$id"$js>
				$section
			</a>
		</li>
LI;
}

function section($name, $data) {
	$id   = id($name);
	if (is_array($data)){
		$rows = table($data);
		$div = "<div class='table-responsive'><table class='table table-striped table-sm'>$rows</table></div>";
	} else if (is_string($data)){
		$div = "<div><pre>$data</pre></div>";
	}

	return <<<DIV
<div id="Section-$id">
	<a id="$id"></a>
	<h1>$name</h1>
	$div
</div>

DIV;

}

function table($data) {
	if (count($data) === 0) {
		return '';
	}
	$first = $data[0];
	if (!is_array($first)) {
		return "<tr><td>None</td></tr>";
	}
	$columns = array_keys($first);
	$thead   = implode("</th><th>", $columns);
	$rows    = [
		"<tr><th>#</th><th>$thead</th></tr>",
	];
	foreach ($data as $n => $d) {
		if (!$d) {
			continue;
		}
		$row    = implode("</td><td>", array_values($d));
		$rows[] = "<tr><td>$n</td><td>$row</td></tr>";
	}

	return implode("\n", $rows);
}

function dropdownItem($file){
	$name = basename(__FILE__);
	$bID = isset($_GET['battleID']) ? $_GET['battleID']: 176;
	echo "<a class='dropdown-item' href='$name?battleID=$bID&file=$file'>$file</a>";
}

?>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
	<div class="dropdown col-md-2">
		<button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
			<?php echo $file; ?>
		</button>
		<div class="dropdown-menu">
			<?php echo implode("\n", array_map('dropdownItem', $files)); ?>
		</div>
	</div>
	<a class="navbar-brand col-md-10" href="#">Pyrite Mission Summary</a>
</nav>

<div class="container-fluid">
	<div class="row">
		<nav class="col-md-2 d-none d-md-block bg-light sidebar">
			<div class="sidebar-sticky">
				<ul class="nav flex-column">
					<?php echo implode("\n", array_map('li', array_keys($menu))); ?>
				</ul>
			</div>
		</nav>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
				<pre><?php
//					echo \Pyrite\Hex::hexToStr(substr($contents, 0, 32));
//					echo \Pyrite\Hex::hexToStr(substr($miss->FileHeader->hex, 0, 32));
//					print_r($miss->FileHeader) ?>
				</pre>
				<?php echo implode("\n", array_map('section', array_keys($menu), $menu)); ?>
			</div>
		</main>
	</div>
</div>
</body>
</html>
