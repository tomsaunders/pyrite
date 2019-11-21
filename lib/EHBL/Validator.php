<?php

namespace Pyrite\EHBL;

class Validator {
	public $messages = [];
	private $packager;

	/**
	 * Validator constructor.
	 * @param $packager Packager
	 */
	public function __construct($packager) {
		$this->packager = $packager;
	}

	public static function compare($zipPackage, $ehmPackage) {
		$v = new Validator($zipPackage);
		$v->compareWith($ehmPackage);
		return $v->messages;
	}

	public function compareWith($otherPackage) {
		$mine = $this->packager->getMissions();
		$othr = $otherPackage->getMissions();
		$mKey = array_keys($mine);
		$oKey = array_keys($othr);

		$diff = array_diff($mKey, $oKey);
		if (count($diff)) {
			$this->messages[] = "Mission files different..." . implode(", ", $diff);
			$this->messages[] = "Zip has " . implode(" ,", $mKey);
			$this->messages[] = "EHM has " . implode(", " , $oKey);
		} else {
			foreach ($mKey as $key) {
				if ($mine[$key] != $othr[$key]) {
					echo $key;
					$this->messages[] = "$key is different between the packages";
					$this->messages   = array_merge($this->messages, $this->missionDiff($mine[$key], $othr[$key]));
				}
			}
		}
	}

	private function missionDiff($hexA, $hexB) {
		if (!\Pyrite\TIE\Mission::validHex($hexA) || !\Pyrite\TIE\Mission::validHex($hexB)){
			return ["Mission file(s) were not in a valid format - encrypted?"];
		}
		$aTIE = new \Pyrite\TIE\Mission($hexA . chr(255) . chr(255)); //TODO die gracefully at the end of thie file
		$bTIE = new \Pyrite\TIE\Mission($hexB . chr(255) . chr(255));

		return $this->compareMissions($aTIE, $bTIE, $aTIE, $bTIE);
	}

	private function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = $this->utf8ize($value);
			}
		} else if (is_string($mixed)) {
			return utf8_encode($mixed);
		}
		return $mixed;
	}

	private function compareMissions($a, $b, $aTIE, $bTIE, $path = '') {
		$out     = [];
		$ref     = new \ReflectionObject($a);
		$exclude = ['Pyrite\PyriteBase'];
		foreach ($ref->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
			if (array_search($property->class, $exclude) !== FALSE) {
				continue;
			}
			$name      = $property->getName();
			$printName = $property->class . '->' . $name;
			$path2     = $path . '->' . $name;
			$aa        = $a->{$name};
			$bb        = $b->{$name};
			if ($this->str($aa) !== $this->str($bb)) {
				if (is_array($aa)) {
					// compare length
					$count = max(count($aa), count($bb));
					for ($i = 0; $i < $count; $i++) {
						if ($this->str($aa[$i]) === $this->str($bb[$i])) {
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
									$out = array_merge($out, $this->compareMissions($aa[$i], $bb[$i], $aTIE, $bTIE, $path3));
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
					$out[] = ["$printName is different", $this->str($aa), $this->str($bb), json_encode($aa), json_encode($bb), $aa, $bb];
				}
			} else {
//				$out[] = ["$printName is same", $aa, $bb];
			}
		}
		return $out;
	}

	private function str($obj) {
		if (is_array($obj) || is_object($obj)) {
			$j = json_encode($obj);
			if (!$j) {
				print_r($obj);
				throw new \Exception("Json encode problem " . json_last_error_msg() . ' trying to handle a ' . get_class(is_object($obj) ? $obj : $obj[0]));
				return rand(1, 100);
			}
			return hash('sha1', $j);
		} else {
			return utf8_encode($obj);
		}
	}

	public static function fromPackage($package) {
		$v = new Validator($package);
		$v->run();
		return $v->messages;
	}

	public function run() {
		$this->checkReadme();
		$this->checkTextFiles();
		$this->checkTextFiles();
	}

	private function checkReadme() {
		$readme = $this->packager->getReadme();
		if (!$readme) {
			$this->messages[] = "Error: no readme found";
			return;
		}

		$expected = [
			"Title", "Author", "Platform", "Number of missions", "Medal",
		];
		foreach ($expected as $e) {
			if (strpos($readme, $e . ":") === FALSE) {
				$this->messages[] = "Warning: expected field $e missing";
			}
		}

		$noEHBL = [
			'mission.lst', 'fronttxt.txt',
		];
		foreach ($noEHBL as $n) {
			if (strpos($readme, $n) === FALSE) {
				$this->messages[] = "Warning: readme mentions $n so it has installation instructions";
			}
		}

		$disclaimer = "THESE LEVELS ARE NOT MADE, DISTRIBUTED, OR SUPPORTED BY LUCASARTS ENTERTAINMENT COMPANY";
		if (strpos($readme, $disclaimer) === FALSE) {
			$this->messages[] = "Error: disclaimer not present";
		}
	}

	private function checkTextFiles() {
		$files = $this->packager->getTextFiles();
		foreach ($files as $file => $data) {
			if (strpos($data, ".tiecorps.org") !== FALSE) {
				$this->messages[] = "Warning: $file mentions tiecorps.org";
			}
		}
	}
}