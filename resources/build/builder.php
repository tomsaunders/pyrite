<?php

namespace Pyrite\Build;

class Builder {
	protected $dir;
	protected $out;
	protected $classStart;
	protected $classEnd;
	protected $constStart;
	protected $constEnd;

	protected $lengthLookup = [];
	protected $platform = '';
	protected $classes = [];
	protected $enums = [];

	// properties related to the currently processing class
	protected $currentClass = '';
	protected $importedTypes = [];
	protected $importedHex = [];

	protected $getSelf = '$this';
	protected $hexClass = '$this';
	protected $debugFN = '__debugInfo';

	protected $singleArrayTemplate = <<<SGL

        \$this->NAME = [];
        \$offset = START_OFF;
        for (\$i = 0; \$i < COUNT_INNER; \$i++) {
            \$t = INNERPROP
            \$this->NAME[] = \$t;
            \$offset += UPDATE_OFF;
        }
SGL;

	protected $singleArrayOutTemplate = <<<SGL

        \$offset = START_OFF;
        for (\$i = 0; \$i < COUNT_INNER; \$i++) {
            \$t = INNERPROP;
            WRITE_OUT
            \$offset += UPDATE_OFF;
        }
SGL;

	protected $constructorHead = <<<TXT

	public function __construct(\$hex, \$tie){
		\$this->hex = \$hex;
		\$this->TIE = \$tie; 
		\$offset = 0;
TXT;

	protected $constructorEnd = <<<TXT
		\$this->afterConstruct();
	}

TXT;

	public function __construct($dir, $out = NULL) {
		$this->dir = $dir;
		$this->out = $out ? $out : $dir;

		$this->classStart = file_get_contents("$dir/build/classStart.txt");
		$this->classEnd   = file_get_contents("$dir/build/classEnd.txt");
		$this->constStart = file_get_contents("$dir/build/constStart.txt");
		$this->constEnd   = file_get_contents("$dir/build/constEnd.txt");
	}

	public function run(array $platforms) {
		foreach ($platforms as $platform) {
			$structs        = file_get_contents("{$this->dir}{$platform}/build/structs.txt");
			$consts         = file_get_contents("{$this->dir}{$platform}/build/const.txt");
			$this->platform = $platform;
			$this->extractClasses(explode("\n", $structs));
			$this->processClasses();
			$this->writeClasses();
			$this->extractConstants(explode("\n", $consts));
			$this->writeConstants();
		}
	}

	/*
	 * For each defined struct, parse into a header line and a series of properties
	 */
	protected function extractClasses(array $structLines) {
		$this->classes = [];
		$heading       = '';
		foreach ($structLines as $line) {
			if (!$heading) {
				$bits = explode(' ', $line);
				if (count($bits) == 2) {
					// variable size
					$bits[] = 'size';
					$bits[] = 0;
				}
				list($struct, $heading, $size, $hex) = $bits;
				$hex = ($hex === 0) ? 0 : str_replace(')', '', $hex);

				$this->classes[$heading] = [
					'name'  => $heading,
					'size'  => $hex,
					'props' => [],
					'funcs' => [],
				];

				$this->lengthLookup[$heading] = $hex;
			} elseif ($line == '{' || $line == '}') {
				continue;
			} elseif (!$line) {
				$heading = '';
			} else {
				$bits = preg_split('/\s+/', $line, 5);
				if (count($bits) < 4) {
					$bits[] = "UNNAMED VARIABLE";
				}
				$rest = count($bits) === 5 ? $bits[4] : '';
				list(, $offset, $type, $name) = $bits;

				$prop = ['name' => $name];
				$l    = $this->typeToLength($type, $this->lengthLookup);

				// an array type like Event[EventsLength] or String[4]
				if (strpos($type, '[')) {
					$type = str_replace(']', '', $type);
					$bits = explode('[', $type);

					list($type, $count) = $bits;

					// if not numeric, assume something like Event[EventsLength] that refers to a previously defined variable
					if ($count && !is_numeric($count)) {
						if (substr($count, -2, 2) === '()') {
							$this->classes[$heading]['funcs'][] = $count;
						}
						if (strpos($count, '-')) {
							list($a, $b) = explode('-', $count);
							$count = $this->getPropertyExp($b, $a);
						}

						$count = $this->getPropertyExp($count);
					}
					$prop['count'] = $count;
				}
				// a length parameter like CHAR<12>
				if (strpos($type, '<')) {
					$typen = str_replace('>', '<', $type);
					$bits  = explode('<', $typen);
					list(, $size) = $bits;

					// if not numeric, assume something like CHAR<Length()> which refers to a defined function
					if ($size && !is_numeric($size)) {
						if (substr($size, -2, 2) === '()') {
							$this->classes[$heading]['funcs'][] = $size;
						}

						$l = $this->getPropertyExp($size);
					}
				}

				$prop = array_merge($prop, [
					'offset' => $offset,
					'type'   => $type,
					'size'   => $l,
					'pv'     => FALSE,
					'debug'  => $this->getPropertyExp($name),
				]);

				if (strpos($rest, '(enum') !== FALSE) {
					$enum          = str_replace(['(enum', ')', ' '], '', $rest);
					$prop['enum']  = $enum ? $enum : $name;
					$rest          = '';
					$prop['debug'] = $this->getEnumLabelExp($name);
				} elseif ($type === 'BYTE') {
					//                    $prop['debug'] = false;
				}

				if ($offset == 'PV') {
					$prop['pv']     = TRUE;
					$prop['offset'] = $this->getVariableExp('offset');
				}

				if ($rest) {
					$prop['comment'] = $this->getComment($rest);
				} else {
					$prop['comment'] = '';
				}

				$this->classes[$heading]['props'][] = $prop;
			}
		}
	}

	protected function typeToLength($type, array $lengths) {
		$mult = 1;
		$type = str_replace("]", "", $type);
		$bits = explode("[", $type);
		if (count($bits) === 2) {
			list($type, $rest) = $bits;
			if (is_numeric($rest)) {
				$mult = $rest;
			}
		}

		if ($type == 'SHORT') {
			return 2 * $mult;
		} elseif (in_array($type, ['BYTE', 'BOOL', 'SBYTE'])) {
			return 1 * $mult;
		} elseif ($type == 'INT') {
			return 4 * $mult;
		} elseif (substr($type, 0, 3) == 'STR') {
			$len = str_replace(['STR', '<', '>'], '', $type);
			return $len;
		} elseif (substr($type, 0, 4) == 'CHAR') {
			$len = str_replace(['CHAR', '<', '>'], '', $type);
			if (strpos($len, '[')) {
				list($len) = explode('[', $len);
			}
			if (substr($len, -2, 2) === '()' || !is_numeric($len)) {
				$len = $this->getPropertyExp($len);
			}
			return $len;
		} elseif (isset($lengths[$type])) {
			return $lengths[$type];
		} else {
//			echo "Unknown length $type\n";
			return 0;
		}
	}

	protected function getPropertyExp($property, $object = '') {
		if (!$object) {
			$object = 'this';
		}
		if (substr($property, 0, 1) == "\$"){
			$property = substr($property, 1);
		}
		return "\${$object}->{$property}";
	}

	protected function getEnumLabelExp($name) {
		return $this->getPropertyExp("get{$name}Label()");
	}

	protected function getVariableExp($name) {
		return "\${$name}";
	}

	// utility functions

	protected function getComment($restOfLine) {
		return ' // ' . trim(str_replace(["\t", '  '], ' ', $restOfLine));
	}

	protected function processClasses() {
		$platform = $this->platform;

		foreach ($this->classes as $heading => $class) {
			$this->currentClass = $heading;

			$lines          = [str_replace(["CLASSNAME", "PLATFORM"], [$heading, $platform], $this->classStart)];
			$len            = $class['size'];
			$variableLength = $len === 0;
			$H              = strtoupper($heading);

			// if len = 0, its dynamic and has to come from a property. If set, it's static and can be a constant
			$lenProp = $variableLength ? ($heading . 'Length') : ($H . '_LENGTH');
			$lines[] = $variableLength ? $this->initProp($lenProp) : $this->initConst($lenProp, $len);
			$lines[] = '';

			foreach ($class['props'] as $prop) {
				$lines[] = $this->getPropertyDeclaration($prop);
			}

			$lines[] = $this->getConstructor($class['props'], $lenProp, $variableLength);

			$lines[] = $this->getDebugInfo($class['props']);

			foreach ($class['props'] as $p) {
				if (isset($p['enum'])) {
					$lines[] = $this->enumLookup($p);
				}
			}

			foreach ($class['funcs'] as $funcName) {
				$lines[] = "\t" . $this->getAbstractFunction($funcName);
			}

			$lines[] = $this->getToHexString($class['props']);

			$lenPropFn = $variableLength ? $this->getPropertyExp($lenProp) : $this->getConst($lenProp);
			$lines[]   = str_replace('LENPROP', $lenPropFn, $this->classEnd);
			$this->writeClass($lines, $heading);
		}
	}

	protected function initProp($name, $value = 0) {
		$v = $this->getVariableExp($name);
		return "\tpublic {$v} = {$value};";
	}

	protected function initConst($lenProp, $value) {
		return "\tconst {$lenProp} = {$value};";
	}

	protected function getPropertyDeclaration($prop) {
		return "\t/** @var {$prop['type']} */" . PHP_EOL .
			"\tpublic \${$prop['name']};{$prop['comment']}";
	}

	protected function getConstructor($props, $lenProp, $updateLength) {
		$lines   = [];
		$lines[] = $this->constructorHead;

		$h  = $this->getVariableExp('hex');
		$o  = $this->getVariableExp('offset');
		$t  = $this->getVariableExp('t');
		$pl = $this->getPropertyExp('Length');

		$last      = '';
		$lastArray = FALSE;
		$lastSize  = 0;
		$offset    = 0;
		$offsetOut = FALSE;

		foreach ($props as $p) {
			$pn   = $this->getPropertyExp($p['name']);
			$prop = "\t\t{$pn} = ";

			if ($p['pv'] && $last && !$lastArray) {
				if ($lastSize === 0) {
					if (!$offsetOut) {
						$lines[]   = "\t\t{$o} = $offset;";
						$offsetOut = TRUE;
					}
					$lastLength = $this->callMethod('getLength', $last);
					$lines[]    = "\t\t{$o} += {$lastLength};";
				} elseif (substr($lastSize, -2, 2) === '()') {
					if (!$offsetOut) {
						$lines[]   = "\t\t{$o} = $offset;";
						$offsetOut = TRUE;
					}
					$lines[] = "\t\t{$o} += {$lastSize};";
				}
			}

			if (isset($p['count'])) {
				$offsetOut = TRUE;
				if ($p['size'] === 0) {
					$p['size'] = $this->callMethod('getLength', $t);
				}

				$search    = ['NAME', 'COUNT_INNER', 'INNERPROP', 'START_OFF', 'UPDATE_OFF', '$offset = $offset;'];
				$replace   = [
					$p['name'],
					$p['count'],
					$this->typeToString($p['type'], $o),
					$p['offset'],
					$p['size'],
					'',
				];
				$subject   = $this->singleArrayTemplate;
				$prop      = str_replace($search, $replace, $subject);
				$lastArray = TRUE;
			} else {
				$prop .= $this->typeToString($p['type'], $p['offset']);
				if ($p['pv'] && $p['size'] !== 0) {
					if (!$offsetOut) {
						$lines[]   = "\t\t{$o} = $offset;";
						$offsetOut = TRUE;
					}
					$prop .= "\n\t\t{$o} += {$p['size']};";
				}
				$lastArray = FALSE;

				if ($p['name'] === 'Length') {
					$prop .= "\n\t\tif ({$pl} === 0) {\n";
					$ac   = $this->callMethod('afterConstruct', $this->getSelf);
					$prop .= "\t\t\t{$ac};\n";
					$prop .= "\t\t\treturn;\n";
					$prop .= "\t\t}";
				}
			}
			if (!$p['pv']) {
				$offset = $p['offset'];
			}

			$lines[]  = $prop;
			$last     = $pn;
			$lastSize = $p['size'];
		}
		if ($updateLength) {
			$lines[] = $this->updateLength($lenProp);
		}
		$lines[] = $this->constructorEnd;

		return implode("\n", $lines);
	}

	// override these in children probably

	protected function callMethod($methodName, $object = '', $args = []) {
		if ($object === NULL){
			$object = $this->getSelf;
		}
		if ($object) {
			$object .= '->';
		}
		$a = implode(", ", $args);
		return "{$object}$methodName($a)";
	}

	protected function typeToString($type, $offset) {
		$tres = substr($type, 0, 3);
		$four = substr($type, 0, 4);
		$five = substr($type, 0, 5);

		$h    = $this->getVariableExp('hex');
		$args = [$h, $offset];

		if ($five == 'SHORT') {
			$m = 'getShort';
		} elseif ($four == 'BYTE') {
			$m = 'getByte';
		} elseif ($four == 'BOOL') {
			$m = 'getBool';
		} elseif ($tres == 'STR') {
			$len    = str_replace(['STR', '<', '>'], '', $type);
			$args[] = $len;

			$m = 'getString';
		} elseif ($five == 'SBYTE') {
			$m = 'getSByte';
		} elseif ($tres == 'INT') {
			$m = 'getInt';
		} elseif ($four == 'CHAR') {
			$len = str_replace(['CHAR', '<', '>'], '', $type);
			if (substr($len, -2, 2) === '()' || !is_numeric($len)) {
				$len = $this->getPropertyExp($len);
			}
			$args[] = $len;

			$m = 'getChar';
		} else {
			$this->importedTypes[] = $type;
			return $this->callConstructor($type, $h, $offset) . ';';
		}

		$this->importedHex[] = $m;
		return $this->callMethod($m, NULL, $args) . ';';
	}

	protected function callConstructor($typeName, $h, $offset) {
		return "new $typeName(substr($h, $offset), \$this->TIE);";
	}

	protected function updateLength($lenProp) {
		$p = $this->getPropertyExp($lenProp);
		$v = $this->getVariableExp('offset');
		return "\t\t{$p} = {$v};";
	}

	protected function getDebugInfo($props) {
		$debugProperties = array_filter($props, function ($p) {
			return $p['debug'];
		});

		$out = $this->getMethodHeader($this->debugFN, 'public', 'object');
		$out .= "\t\treturn " . $this->arrayOutput($debugProperties, 'name', 'debug');
		$out .= "\n\t}\n";

		return $out;
	}

	protected function getMethodHeader($name, $visibility = 'protected', $returnType = '') {
		return "\t{$visibility} function {$name}() {\n";
	}

	protected function arrayOutput($array, $keyName, $valueName) {
		$out   = "[\n";
		$debug = array_map(
			function ($p) use ($keyName, $valueName) {
				return "\t\t\t\"{$p[$keyName]}\" => {$p[$valueName]}";
			},
			$array
		);
		$out   .= implode(",\n", $debug);
		$out   .= "\t\t];";
		return $out;
	}

	protected function enumLookup(array $prop) {
		$fn = "get{$prop['name']}Label";
		$en = strtoupper($prop['enum']);
		$tp = $this->getPropertyExp($prop['name']);
		return <<<ELU
        protected function {$fn}() {
            return isset($tp) && isset(Constants::\${$en}[$tp]) ? Constants::\${$en}[$tp] : "Unknown";
        }

ELU;
	}

	protected function getAbstractFunction($funcName) {
		return "abstract protected function $funcName;\n";
	}

	protected function getToHexString($props) {
		$lines     = [];
		$last      = '';
		$lastArray = FALSE;
		$lastSize  = 0;
		$lines[]   = $this->getMethodHeader('toHexString');

		$h  = $this->getVariableExp('hex');
		$o  = $this->getVariableExp('offset');
		$i  = $this->getVariableExp('i');
		$t  = $this->getVariableExp('t');
		$pl = $this->getPropertyExp('Length');

		$ih      = $this->initVar('hex', "\"\"", TRUE);
		$io      = $this->initVar('offset', 0);
		$lines[] = "\t\t{$ih}\n";
		$lines[] = "\t\t{$io}";

		$offset    = 0;
		$offsetOut = FALSE;
		foreach ($props as $p) {
			if ($p['pv'] && $last && !$lastArray) {
				if ($lastSize === 0) {
					if (!$offsetOut) {
						$lines[]   = "\t\t{$o} = $offset;";
						$offsetOut = TRUE;
					}
					$lastLength = $this->callMethod('getLength', $last);
					$lines[]    = "\t\t{$o} += {$lastLength};";
				} elseif (substr($lastSize, -2, 2) === '()') {
					if (!$offsetOut) {
						$lines[]   = "\t\t{$o} = $offset;";
						$offsetOut = TRUE;
					}
					$lines[] = "\t\t{$o} += {$lastSize};";
				}
			}

			if (isset($p['count'])) {
				$offsetOut = TRUE;

				$arrayOff = "[{$i}]";
				if ($p['size'] === 0) {
					$p['size'] = $this->callMethod('getLength', $t);
				}
				$prop      = $this->getPropertyExp($p['name']) . $arrayOff;
				$out       = $this->typeToStringOut($p['type'], $p['name'] . $arrayOff, $o);
				$search    = ['NAME', 'COUNT_INNER', 'INNERPROP', 'WRITE_OUT', 'START_OFF', 'UPDATE_OFF'];
				$replace   = [$p['name'], $p['count'], $prop, $out, $p['offset'], $p['size']];
				$subject   = $this->singleArrayOutTemplate;
				$lines[]   = str_replace($search, $replace, $subject);
				$lastArray = TRUE;
			} else {
				$lines[] = "\t\t" . $this->typeToStringOut($p['type'], $p['name'], $p['offset']);
				if ($p['pv'] && $p['size'] !== 0) {
					if (!$offsetOut) {
						$lines[]   = "\t\t{$o} = $offset;";
						$offsetOut = TRUE;
					}
					$lines[] = "\t\t{$o} += {$p['size']};";
				}
				if ($p['name'] === 'Length') {
					$lines[] = "\t\tif ({$pl} === 0) {\n\t\t\treturn;\n\t\t}";
				}
			}
			if (!$p['pv']) {
				$offset = $p['offset'];
			}
			$last     = $this->getPropertyExp($p['name']);
			$lastSize = $p['size'];
		}
		$lines[] = "\t\treturn {$h};";
		$lines[] = "\t}\n";

		return implode("\n", $lines);
	}

	// end overrides

	protected function initVar($name, $value = 0, $constant = FALSE) {
		$v = $this->getVariableExp($name);
		return "{$v} = {$value};";
	}

	protected function typeToStringOut($type, $prop, $offset) {
		$h    = $this->getVariableExp('hex');
		$p    = $this->getPropertyExp($prop);
		$args = [$h, $p, $offset];

		if ($type == 'SHORT') {
			$m = 'writeShort';
		} elseif ($type == 'BYTE') {
			$m = 'writeByte';
		} elseif ($type == 'BOOL') {
			$m = 'writeBool';
		} elseif (substr($type, 0, 3) == 'STR') {
			$len    = str_replace(['STR', '<', '>'], '', $type);
			$args[] = $len;

			$m = 'writeString';
		} elseif ($type == 'SBYTE') {
			$m = 'writeSByte';
		} elseif ($type == 'INT') {
			$m = 'writeInt';
		} elseif (substr($type, 0, 4) == 'CHAR') {
			$len = str_replace(['CHAR', '<', '>'], '', $type);
			if (substr($len, -2, 2) === '()' || !is_numeric($len)) {
				$len = $this->getPropertyExp($len);
			}
			$args[] = $len;

			$m = 'writeChar';
		} else {
			$m = 'writeObject';
		}

		$this->importedHex[] = $m;
		return $this->callMethod($m, NULL, $args) . ';';
	}

	protected function getConst($name) {
		return "self::{$name}";
	}

	protected function writeClass(array $lines, $className) {
		$fileContents = implode("\n", $lines);
		file_put_contents("{$this->out}{$this->platform}/gen/{$className}Base.php", $this->filterFileContents($fileContents));
		echo htmlentities($fileContents);

		$impl     = "<?php
namespace Pyrite\TIE;

class {$className} extends {$className}Base
{

}
";
		$implPath = "{$this->out}{$this->platform}/{$className}.php";
		if (!file_exists($implPath)) {
			file_put_contents($implPath, $this->filterFileContents($impl));
		}
		$this->importedTypes = [];
		$this->importedHex   = [];
		$this->currentClass  = '';
	}

	protected function filterFileContents($contents) {
		return $contents;
	}

	protected function writeClasses() {
		file_put_contents(
			"{$this->out}{$this->platform}/build/structs.json",
			json_encode($this->classes, JSON_PRETTY_PRINT)
		);
		echo json_encode($this->classes, JSON_PRETTY_PRINT);
	}

	protected function extractConstants($lines) {
		$this->enums = [];
		$heading     = '';
		foreach ($lines as $line) {
			$line = trim($line);
			if (!$heading || !$line) {
				$heading = $line;
				if ($heading) {
					$this->enums[$heading] = [];
				}
			} else {
				list($value, $label) = preg_split('/\s+/', $line, 2);
				$this->enums[$heading][$value] = $label;
			}
		}
	}

	protected function writeConstants() {
		$fileContents = str_replace('PLATFORM', $this->platform, $this->constStart);
		foreach ($this->enums as $name => $items) {
			$name         = strtoupper($name);
			$fileContents .= "\n\tpublic static \$$name = [\n";
			foreach ($items as $value => $label) {
				if (substr($value, 0, 3) === 'Var') {
					$fileContents .= "\t\t// $value $label\n";
				} else {
					$value        = hexdec($value);
					$fileContents .= "\t\t$value => \"$label\",\n";
				}
			}
			$fileContents .= "\t];\n";
		}
		$fileContents .= $this->constEnd;
		file_put_contents("{$this->out}{$this->platform}/Constants.php", $this->filterFileContents($fileContents));
		file_put_contents(
			"{$this->out}{$this->platform}/build/const.json",
			json_encode($this->enums, JSON_PRETTY_PRINT)
		);
		// echo htmlentities($fileContents);
		echo json_encode($this->enums, JSON_PRETTY_PRINT);
	}

	protected function camelToKebab($camel) {
		$c = strlen($camel);
		$camel .= '?';
		$ucase = strtoupper($camel);
		$lcase = strtolower($camel);
		$kebab = $lcase[0];

		for ($i = 1; $i < $c; $i++){
			$l = $i - 1;
			$n = $i + 1;
			$wasUp = $ucase[$l] === $camel[$l];
			$wasLo = $lcase[$l] === $camel[$l];
			$isUp = $ucase[$i] === $camel[$i];
			$isLo = $lcase[$i] === $camel[$i];
			$nUp = $ucase[$n] === $camel[$n];
			$nLo = $lcase[$n] === $camel[$n] && $camel[$n] !== '?';

			if ($wasLo && $isUp){
				$kebab .= '-';
			} else if ($wasUp && $isUp && $nLo) {
				$kebab .= '-';
			}
			$kebab .= $lcase[$i];
		}
		return $kebab;
	}

	public function test() {
		$tests = [
			['Mission', 'mission'],
			['GlobalGoal', 'global-goal'],
			['PreMissionQuestions', 'pre-mission-questions'],
			['TIEString', 'tie-string'],
			['GoalFG', 'goal-fg']
		];
		foreach ($tests as list($in, $out)){
			$k = $this->camelToKebab($in);
			if ($k === $out){
				echo "$out OK\n";
			} else {
				echo "$in became $k not $out SAD\n\n";
			}
		}
	}
}
