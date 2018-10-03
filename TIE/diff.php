<?php
set_error_handler(function ($severity, $message, $file, $line) {
	if (error_reporting() & $severity) {
		throw new ErrorException($message, 0, $severity, $file, $line);
	}
});

function pyriteLoader($class) {
	$ds      = DIRECTORY_SEPARATOR;
	$rootDir = dirname(dirname(__FILE__)) . $ds;
	$path    = [$rootDir];

	// Pyrite\TIE\MissionBase
	$class = str_replace('Pyrite\\', '', $class); //strip project name;
	// TIE\MissionBase
	$bits = explode('\\', $class);
	if (count($bits) === 2) {
		list($platform, $class) = $bits;

		$path[] = $platform . $ds;
		if (strpos($class, 'Base')) {
			$path[] = 'gen' . $ds;
		}
	}

	$path[] = $class . '.php';
	$path   = implode('', $path);
	require_once $path;
}

spl_autoload_register('pyriteLoader');

echo "<pre style='white-space: pre-wrap;'>";
$orig = new \Pyrite\TIE\Mission(file_get_contents("../diff/IL2.tie") . chr(255) . chr(255));
$fixt = new \Pyrite\TIE\Mission(file_get_contents("../diff/IL2.fixed.tie") . chr(255) . chr(255));

function compare($a, $b, $aTIE, $bTIE, $path = '') {
	$out     = [];
	$ref     = new ReflectionObject($a);
	$exclude = ['Pyrite\PyriteBase'];
	foreach ($ref->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
		if (array_search($property->class, $exclude) !== FALSE) {
			continue;
		}
		$name      = $property->getName();
		$printName = $property->class . '->' . $name;
		$path2     = $path . '->' . $name;
		$aa        = $a->{$name};
		$bb        = $b->{$name};
		if (str($aa) !== str($bb)) {
			if (is_array($aa)) {
				// compare length
				$count = max(count($aa), count($bb));
				for ($i = 0; $i < $count; $i++) {
					if (str($aa[$i]) === str($bb[$i])) {
						continue;
					}

					$path3 = $path2 . "[$i]";
					if (!isset($aa[$i])) {
						$out[] = ["Position $i missing from original in $printName", $bb[$i]];
					} elseif (!isset($bb[$i])) {
						$out[] = ["Position $i missing from fix in $printName", $bb[$i]];
					} else {
						if (is_object($aa[$i])) {
							if ($aa[$i] instanceof \Pyrite\PyriteBase && $aa[$i]->diffLimit) {
								$aa[$i]->TIE = $aTIE;
								$bb[$i]->TIE = $bTIE;
								$out[]       = [
									'position' => "$path3 is different",
									'from'     => (string)$aa[$i],
									'to'       => (string)$bb[$i],
								];
							} else {
								$out = array_merge($out, compare($aa[$i], $bb[$i], $aTIE, $bTIE, $path3));
							}
						} else {
							$out[] = ["$printName is different at $i", $aa[$i], $bb[$i]];
						}
					}
				}
				// compare all items
				// compare
				//                $out = array_merge($out, compare($aa, $bb, $path2), [(string) $aa]);
			} else {
				$out[] = ["$printName is different", $aa, $bb];
			}
		}
	}
	return $out;
}

function str($obj) {
	return hash('sha1', json_encode($obj));
}

print_r(compare($orig, $fixt, $orig, $fixt));
echo "</pre>";
