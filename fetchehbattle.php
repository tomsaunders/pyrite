<?php

/*	The functions included here will take a TIE Corps Battle ID and download the TIE format missions from the associated battle.
	It does so by reading the battle info page to determine the Platform, SubGroup, and Battle Number; takes this info and turns
	it into the battle's download link. The mission extraction function then pulls only the missions from the ZIP file and places
	them in a directory tree similar to the TIE Corps database.

	This was created to aid "GN Pickled Yoda" in updating the mission records in his TIE mission analyzer.

	All code created by "AD Xavier Sienar", aka Joseph Lathem.	*/

function extractMissions($zipfile) {

		// Download file (meh...)
		file_put_contents("Tmpfile.zip", fopen($zipfile, 'r'));
		$tmpfile = "Tmpfile.zip";

		// Set up directory tree and the zip IO
		$zippath = explode('/', $zipfile);

		if (is_numeric(substr($zippath[7], -7, -4))) { $number = substr($zippath[7], -7, -4); }
		elseif (is_numeric(substr($zippath[7], -6, -4))) { $number = substr($zippath[7], -6, -4); }
		elseif (is_numeric(substr($zippath[7], -5, -4))) { $number = substr($zippath[7], -5, -4); }

		// Set path if needed!!!	(Default: TIE TC 1 = "missions/TIE/TC/1/")
		$path = "missions/".$zippath[5]."/".$zippath[6]."/".$number."/";
		if (file_exists($path) == false) { mkdir($path, 0700); }

		$files = array();
		$zip = new ZipArchive;
		$result = $zip->open($tmpfile);

		if ($result === true) {
			for($i = 0; $i < $zip->numFiles; $i++) {
				$entry = $zip->getNameIndex($i);
				// Check if the entry contains the TIE file extension, Add to array if it matches
				if (strpos(strtolower($entry), ".tie")) {
					array_push($files, $entry);
				}
			}
			// Extract the matching files
			if ($zip->extractTo($path, $files) === true) {
				return true;
			} else {
				echo "\nUnable to extract ".$zipfile." to ".$path.".\n<br>";
				return false;
			}
			$zip->close();
		} else {
			echo "\nUnable to open ".$zipfile.".\n<br>";
			return false;
		}
}

function getBattleZip($battleID) {

	$infourl = "http://tc.emperorshammer.org/download.php?id=".$battleID."&type=info";
	$baseurl = "http://tc.emperorshammer.org/downloads/battles/";

	$errors = false;

	// Read ALL characters starting from the 7653th character (beyond all the header stuff)
	$section = file_get_contents($infourl, NULL, NULL, 7652);

	// Grab Platform and Subgroup numbers
	$subsection = substr($section, 0, 7);
	$platform = substr($subsection, 0, 1);
	$subgroup = substr($subsection, 5, 2);

	// Only keep both SG digits if we need them
	if (substr($subgroup, 1, 1) == "\"") {
		$subgroup = substr($subgroup, 0, 1);
		$sgdigits = 1;
	} else {
		$sgdigits = 2;
	}

	// The fun part, figuring out what those numbers mean
	switch ($platform) {
		case "1":
		$platform = "TIE";
		break;
		case "2":
		$platform = "XvT";
		break;
		case "3":
		$platform = "BoP";
		break;
		case "4":
		$platform = "XWA";
		break;
		// We can't use these below -- populated them anyway :P
		case "5":
		$platform = "XW";
		$errors = true;
		break;
		case "6":
		$platform = "IA";
		$errors = true;
		break;
		case "7":
		$platform = "SWGB";
		$errors = true;
		break;
		case "8":
		$platform = "JA";
		$errors = true;
		break;
		default:
		$errors = true;
	}

	switch ($subgroup) {
		case "1":
		$subgroup = "TC";
		break;
		case "2":
		$subgroup = "IW";
		break;
		case "3":
		$subgroup = "DB";
		break;
		case "4":
		$subgroup = "ID";
		break;
		case "5":
		$subgroup = "IS";
		break;
		case "6":
		$subgroup = "CD";
		break;
		case "7":
		$subgroup = "HF";
		break;
		case "8":
		$subgroup = "FMC";
		break;
		case "9":
		$subgroup = "CAB";
		break;
		case "10":
		$subgroup = "FCHG";
		break;
		case "11":
		$subgroup = "DIR";
		break;
		case "12":
		$subgroup = "BHG";
		break;
		case "13":
		$subgroup = "free";
		break;
		default:
		$errors = true;
	}

	// Now we use the Platform and SG info to find where to look for the battle number
	$bnstart = ((strlen($battleID) * 4) + (strlen($subgroup) * 2) + (strlen($platform) * 2) + $sgdigits + 1580);

	$battlenumber = substr($section, $bnstart, 3);

	if (substr($battlenumber, 2, 1) == "/") {
		$battlenumber = substr($battlenumber, 0, 1);
	}
	elseif (substr($battlenumber, 2, 1) == "<") {
		$battlenumber = substr($battlenumber, 0, 2);
	}

	// Catch errors and display warning
	if ($errors) {
		echo $battleID." --- ".$platform."-".$subgroup." ".$battlenumber." --- ERROR!\n<br>";
		return false;
	}

	// "free missions" break the simple naming scheme, this fixes that
	if ($subgroup == "free") {
		$subgroup2 = "F";
	}
	else {
		$subgroup2 = $subgroup;
	}

	// Finally! We have our target.
	$zipfile = $baseurl.$platform."/".$subgroup."/".$platform.$subgroup2.$battlenumber.".zip";

	if (extractMissions($zipfile)) {
		$extracted = $platform."-".$subgroup." ".$battlenumber." --- SUCESS!\n<br>";
	} else {
		$extracted = $battleID." --- ".$platform."-".$subgroup." ".$battlenumber." --- ".$zipfile." --- ERROR!\n<br>";
	}

	echo $extracted."\n";
}

// Usage Examples
/*
getBattleZip(670); // XvT-IW 8
getBattleZip(139); // TIE-CAB 3
getBattleZip(1245); // BoP-FCHG 3
getBattleZip(1455); // TIE-CD 1
getBattleZip(674); // TIE-FCHG 5
getBattleZip(316); // TIE-BHG 2
getBattleZip(1436); // TIE-free 287
*/
?>
