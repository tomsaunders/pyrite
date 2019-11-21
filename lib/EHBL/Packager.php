<?php

namespace Pyrite\EHBL;

class Packager {
	private $dir;
	/**
	 * @var BattleIndex
	 */
	public $ehb;
	private $missionFiles = [];
	private $files = [];

	public function __construct($dir, $ehb, $missionFiles, $resourceFiles) {
		$this->dir = $dir;
		$this->loadEHB($ehb);
		$this->loadMissions($missionFiles);
		$this->loadResources($resourceFiles);
	}

	/**
	 * @param $ehb BattleIndex
	 */
	private function loadEHB($ehb) {
		$this->ehb                 = $ehb;
		$this->files['Battle.ehb'] = $ehb->toHex();
	}

	private function loadMissions($files) {
		foreach ($files as $file) {
			$this->missionFiles[basename($file)] = file_get_contents($this->dir . $file);
		}
	}

	private function loadResources($files) {
		foreach ($files as $file) {
			$this->files[basename($file)] = file_get_contents($this->dir . $file);
		}
	}

	/**
	 * @param $battle Battle
	 * @return string filename
	 */
	public static function fromBattle($battle) {
		return new Packager(
			$battle->folder,
			$battle->getBattleIndex(),
			$battle->missionFiles,
			$battle->resourceFiles
		);
	}

	public function __toString() {
		$f = implode(", ", array_keys($this->files));
		return "{$this->dir} with files {$f}";
	}

	public function getReadme() {
		$options = ['readme.txt', 'README.TXT', 'Readme.txt', 'readme.rtf', 'README.RTF'];
		foreach ($options as $o) {
			if (isset($this->files[$o])) {
				return $this->files[$o];
			}
		}
		error_log("No readme found in {$this}");
		return '';
	}

	public function getMissions() {
		return $this->missionFiles;
	}

	public function getTextFiles() {
		return array_filter($this->files, function ($value, $key) {
			return strpos(strtolower($key), '.txt') !== FALSE;
		}, ARRAY_FILTER_USE_BOTH);
	}

	public function package() {
		$zipPath = $this->toZip();
		$ehmPath = $this->toEHM();
		return [$zipPath, $ehmPath];
	}

	public function toZip() {
		return $this->createZip($this->ehb->key . '.zip');
	}

	private function createZip($filename, $makeEHM = false) {
		$filename = $this->dir . $filename;
		$zip      = new \ZipArchive();

		if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE) {
			exit("cannot open <$filename>\n");
		}

		foreach ($this->missionFiles as $name => $data) {
			$zip->addFromString($name, $data);
		}
		foreach ($this->files as $name => $data) {
			if (!$makeEHM && $name === 'Battle.ehb') continue;
			$zip->addFromString($name, $data);
		}

		$zip->close();
		return $filename;
	}

	public function toEHM() {
		$this->encodeMissions();
		return $this->createZip($this->ehb->key . '.ehm', TRUE);
	}

	private function encodeMissions() {
		// TODO probably this should work based on the Mission objects not the files
		$offset    = $this->ehb->encryptionOffset;
		$originals = $this->missionFiles;
		foreach ($originals as $key => $original) {
			$enc = '';
			for ($i = 0; $i < strlen($original); $i++) {
				$value   = unpack('c', $original[$i])[1];
				$decrypt = $offset ^ $value;
				$enc     .= pack('c', $decrypt);
			}
			$this->missionFiles[$key] = $enc;
		}
	}
}
