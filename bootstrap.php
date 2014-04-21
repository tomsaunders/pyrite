<?php
function __autoload($class){
	$class = str_replace('Pyrite\\', '', $class); //strip project name;
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require_once($class . '.php');
}

$source = "0blank nothing at all
1fg (1) Rebel None Valkyrie
1fgA set position 1
1fgB set as player craft (rescued)
1fgC (captured)
1fgD 6 in flight. player is position 3
1fgE 2 more waves
1fgF X-Wing
1fgG Y-Wing
1fgH TIE Defender
1fgI Imperial
1fgJ Ace AI
1fgK formation
1fgL colour
1fgM just secret officer at briefing
1fgN obeys player
1fgO special cargo position two
1fgP special pos random
1fgQ ship status modifier
1fgR ship missile
1fgS ship beam
1fgT primary goal 100% completed
1fgU primary goal at least one completed
1fgV secondary goal at least one dropped off
1fgW secret goal all but one run out of missiles
1fgX bonus goal 50% destroyed
1fgY bonus goal 1000 points
1fgZ arrival easy only
1fgZarrival arrival conditions added
1fgZdeparture dep stop cond added
1fgZdepartureB dep cond added
1fgZdepartureC methods mothership FG1
1fgZorderA add first order - attack ship type TF TI TB TA at 90% throttle
1fgZorderB second and third order
1fgZnavigation add waypoints
1fgZnavigationB change first waypoint
1fgZnavigationC unenable a point
1fgZnavigationD waypoint 1 all set back to 1
1fgZnavigationE waypoint 1 1 set to 2, 1-1 set to 3, 1-1 set to 4
1fgZnavigationF waypoint 2 all set to -1 -2 -3, #3 set to 1.25 2.5 3.75
2fgA second flight group 3x6 rebel X-Wing secondus many variables set
3fgA third flight group duplicate
4fgA fourth flight group duplicate
4fgB added messages and some briefing
4fgC added global goals etc
2messA added message text
2messB increased message text
2messC changed colour
2messD added message conditions and changed colour
2messE changed conditions
2messF OR condition
2messG changed time delay
2messH no delay
D6M1SHU TIETC236 M1
D6M2PH  TIETC236 M2
D6M3PH TIETC236 M3
D6M4PH TIETC236 M4
D6M5PH TIETC236 M5";
$source = explode("\n", $source);
$desc = array();
foreach ($source as $line){
    $line = explode(" ", $line, 2);
    $desc[$line[0] . '.tie'] = $line[1];
}

/** wrap substr with code which only returns the string up to the first null byte */
function TIE_substr($source, $start, $length){
    $substr = substr($source, $start, $length);
    if ($end = strpos($substr, chr(0))){
        $substr = substr($substr, 0, $end);
    }
    return $substr;
}

function getBool($chr, $startPos = null){
	if ($startPos !== null){
		$chr = substr($chr, $startPos, 1);
	}
	return ord($chr) ? TRUE : FALSE;
}

function getByte($chr, $startPos = null){
	if ($startPos !== null){
		$chr = substr($chr, $startPos, 1);
	}
	return unpack('Cbyte', $chr)['byte'];
}

function getSByte($chr, $startPos = null){
	if ($startPos !== null){
		$chr = substr($chr, $startPos, 1);
	}
	return unpack('csbyte', $chr)['sbyte'];
}

function getChar($chr){
	return unpack('cchar', $chr)['char'];
}

function getShort($str, $startPos = null){
	if ($startPos !== null){
		$str = substr($str, $startPos, 2);
	}
	return unpack('sshort', $str)['short'];
}

function getInt($str, $startPos = null){
	if ($startPos !== null){
		$str = substr($str, $startPos, 4);
	}
	return unpack('lint', $str)['int'];
}

/**
 * Get a string from the provided hex string. Terminate the string at the first null/chr(0) character
 * @param $str
 * @param int $start   If set, perform a substr from this position before looking for the null characters
 * @param int $length  If set, perform a substr to this length from the start position
 * @return string
 */
function getString($str, $start = 0, $length = null){
	if ($start || $length){
		$str = substr($str, $start, $length);
	}
	$end = strpos($str, chr(0),1);
	if ($end){
		$str = substr($str, 0, $end);
	}
	return trim($str);
}