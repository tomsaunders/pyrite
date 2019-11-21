<?php

namespace Pyrite\EHBL;

class Battle
{
  public $platform;
  public $type;
  public $num;
  public $title;
  public $folder;
  public $missionFiles = [];
  public $resourceFiles = [];

  public function __construct($platform, $type = BattleType::UNKNOWN, $num = 0, $title = '', $folder = '', $missionFiles = [], $resourceFiles = [])
  {
    $this->platform = $platform;
    $this->type = $type;
    $this->num = $num;
    $this->title = $title;
    $this->folder = $folder;
    $this->missionFiles = $missionFiles;
    $this->resourceFiles = $resourceFiles;
  }

  public static function fromZipUpload($fileData)
  {
    $path = $fileData['tmp_name'];
    $name = str_replace('.zip', '', $fileData['name']);

    return self::fromZip($path, $name);
  }

  public static function fromEHMUpload($fileData)
  {
    $path = $fileData['tmp_name'];
    $name = str_replace('.ehm', '', $fileData['name']);

    return self::fromEHM($path, $name);
  }

  public static function fromEHM($path, $name = null)
  {
    $battle = self::fromZip($path, $name);
    $battle->decrypt();
    return $battle;
  }

  public static function fromZip($path, $name = null)
  {
    $name = $name ? $name : basename($path);
    $rand = date("Ymd");
    $dir = "/tmp/$rand$name/";
    if (!file_exists($dir)) {
      mkdir($dir);
    }

    list($platform, $type, $num) = self::parseKey($name);

    $zip = new \ZipArchive();
    if ($zip->open($path) === true) {

      $manifests = [];
      $missions = [];
      $resources = [];

      for ($f = 0; $f < $zip->numFiles; $f++) {
        $filename = $zip->getNameIndex($f);
        $lcFile = strtolower($filename);
        //                copy("zip://$path#$filename", $dir . $filename);
        $zip->extractTo($dir, [$filename]);

        $ext = substr($lcFile, -4, 4);
        if ($ext === '.tie') {
          $missions[] = $filename;
        } elseif ($ext === '.lfd' || $ext === '.lst') {
          $manifests[] = $filename;
          $resources[] = $filename;
        } else {
          $resources[] = $filename;
        }
      }

      $zip->close();

      switch ($platform) {
        case Platform::TIE:
          return \Pyrite\TIE\Battle::fromFolder($type, $num, $dir, $manifests, $missions, $resources);
        case Platform::XWA:
          return \Pyrite\XWA\Battle::fromFolder($type, $num, $dir, $manifests, $missions, $resources);
      }
    }
  }

  public function decrypt()
  {
    $ehb = $this->getBattleIndex();
    $offset = $ehb->encryptionOffset;
    $originals = $this->missionFiles;
    foreach ($originals as $filename) {
      $original = file_get_contents($this->folder . $filename);
      $enc = '';
      for ($i = 0; $i < strlen($original); $i++) {
        $value = unpack('c', $original[$i])[1];
        $decrypt = $offset ^ $value;
        $enc .= pack('c', $decrypt);
      }
      file_put_contents($this->folder . $filename, $enc);
    }
  }

  public function getBattleIndex()
  {
    if (in_array('Battle.ehb', $this->resourceFiles)) {
      return BattleIndex::fromHex(file_get_contents($this->folder . 'Battle.ehb'), $this->name());
    } else {
      return $this->createBattleIndex();
    }
  }

  public function createBattleIndex()
  {
    return BattleIndex::build($this->name(), $this->title, $this->missionFiles);
  }

  public function name()
  {
    return $this->platform . $this->type . $this->num;
  }

  public static function parseKey($key)
  {
    $platform = $type = $num = '';
    foreach (Platform::$ALL as $p) {
      if (substr($key, 0, 3) === $p) {
        $platform = $p;
        $key = substr($key, 3);
        break;
      }
    }
    foreach (BattleType::$ALL as $t) {
      if (strpos($key, $t) !== false) {
        $type = $t;
        $key = str_replace($t, '', $key);
        break;
      }
    }
    $num = (int) $key;
    if (!$platform || !$type || !$num) {
      error_log("Couldn't parse $key");
    }
    return [$platform, $type, $num];
  }
}
