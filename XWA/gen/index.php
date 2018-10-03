<pre><?php
//	$consts= file_get_contents('const.txt');
//	$lines = explode("\n", $consts);
//
//	$heading = '';
//	$out = array();
//	foreach ($lines as $line){
//		if (!$heading) {
//			$heading = trim($line);
//			$out[$heading] = array();
//		} else if ($line === ''){
//			$heading = '';
//		} else if ($line === '{' || $line === '}' || $line === '...'){
//			continue;
//		} else {
//			list($offset, $name) = preg_split('/\s+/', $line, 2);
//			$out[$heading][$offset] = $name;
//		}
//	}
//	
//	foreach ($out as $heading => $items){
//		echo "\n\npublic static $" . strtoupper($heading) . " = array(\n";
//		
//		foreach ($items as $offset => $label){
//			$int = hexdec($offset);
//			echo "$int => '$label',\n";
//		}
//		echo ");\n";
//	}
//
//	return;

	$structs    = file_get_contents('structs.txt');
	$classStart = file_get_contents('classStart.txt');
	$classEnd   = file_get_contents('classEnd.txt');

	$lines = explode("\n", $structs);

	$heading = '';
	$out = array();
	$lengths = array();
	foreach ($lines as $line){
		if (!$heading){
			list($struct, $heading, $size, $hex) = explode(' ', $line);
			if (!$size){
				$out["ERRORS"][] = $line;
			}
			$hex = str_replace(')', '', $hex);
			$out[$heading] = array();
			$lengths[$heading] = $hex;
		} else if ($line == '{' || $line == '}'){
			continue; //do naaaaahthing
		} else if (!$line){
			$heading = '';
		} else {
			list(, $offset, $type, $name) = preg_split('/\s+/', $line);
			$out[$heading][$offset] = array($type, $name);
			if (!$name){
				$out["ERRORS"][] = $line;
			}
		}
	}

	function typeToString($type, $offset){
		if ($type == 'SHORT'){
			return '$this->getShort($hex, ' 	. $offset . ');';
		} else if ($type == 'BYTE'){
			return '$this->getByte($hex, ' 		. $offset . ');';
		} else if ($type == 'BOOL'){
			return '$this->getBool($hex, ' 		. $offset . ');';
		} else if (substr($type, 0, 3) == 'STR'){
			$len = str_replace(array('STR','(',')'),'',$type);
			return '$this->getString($hex, ' 	. $offset . ', ' . $len . ');';
		} else if ($type == 'SBYTE'){
			return '$this->getSByte($hex, ' 	. $offset . ');';
		} else if ($type == 'INT'){
			return '$this->getInt($hex, ' 		. $offset . ');';
		} else if ($type == 'CHAR'){
			return '$this->getChar($hex, ' 		. $offset . ');';
		} else {
			return 'new '.$type.'(substr($hex, '. $offset . '));';
		}
	}

	function typeToLength($type, $lengths){
		if ($type == 'SHORT'){
			return 2;
		} else if (in_array($type, array('BYTE', 'BOOL', 'SBYTE', 'CHAR'))){
			return 1;
		} else if ($type == 'INT'){
			return 4;
		} else if (substr($type, 0, 3) == 'STR'){
			$len = str_replace(array('STR','(',')'),'',$type);
			return $len;
		} else if (isset($lengths[$type])){
			return $lengths[$type];
		} else {
			return 0;
		}
	}

	foreach ($out as $heading => $stuff){
		$lines = array(str_replace("CLASSNAME", $heading, $classStart) . PHP_EOL);
		$len = $lengths[$heading];

		$lines[] = "\tconst " . strtoupper($heading) . '_LENGTH = ' . $len . ';' . PHP_EOL;
		foreach ($stuff as $deets){
			list($type, $name) = $deets;
			$lines[] = "\tpublic $" . $name . ';';
		}
		$lines[] = '	public function __construct($hex){';
		foreach ($stuff as $offset => $deets){
			list($type, $name) = $deets;
			$prop = "\t\t" . '$this->' . $name . ' = ';

			if (strpos($type, '[')){
				list($type, $count) = explode('[', $type);
				$count = str_replace(']', '', $count);
				if ($count > 1){
					$prop .= 'array();' . PHP_EOL;
					$prop .= '		for ($i = 0; $i < ' . $count . '; $i++){' . PHP_EOL;
					$l = typeToLength($type, $lengths);
					$off = $offset . ' + $i';
					if ($l > 1) $off .= ' * ' . $l;

					$prop .= "\t\t\t" . '$this->' . $name . '[] = ';
					$prop .= typeToString($type, $off);
					$prop .= PHP_EOL;
					$prop .= "		}" . PHP_EOL;
				} else {
					$prop .= typeToString($type, $offset);
				}
			} else {
				$prop .= typeToString($type, $offset);
			}

			$lines[] = $prop;
		}
		$lines[] = '	}';



		$lines[] = str_replace('HEADER_LENGTH', strtoupper($heading) .'_LENGTH', $classEnd);
		$file = implode("\n", $lines);
		file_put_contents("$heading.php", $file);
		echo htmlentities($file);
	}
	print_r($out);
?>
</pre>