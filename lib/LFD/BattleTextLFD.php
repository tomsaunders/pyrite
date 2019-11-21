<?php
namespace Pyrite\LFD;

class BattleTextLFD extends TextLFD
{
    public $BattleName;
    public $CutsceneName;
    public $TitleBattle1;
    public $TitleBattle2;
    public $TitleCutscene1;
    public $TitleCutscene2;
    public $DeltName;
    public $SystemName;
    public $Frame;
    public $MissionFilenames = [];
    public $MissionDescriptions = [];

    public function __construct($hex)
    {
        parent::__construct($hex);
        if (count($this->Strings)) {
					list($this->BattleName, $this->CutsceneName) = $this->Strings[0];
					list($this->TitleBattle1, $this->TitleBattle2, $this->TitleCutscene1, $this->TitleCutscene2) = $this->Strings[1];
					list($this->DeltName, $this->SystemName, $this->Frame) = $this->Strings[2];
					$this->MissionFilenames    = $this->Strings[3]->SubStrings;
					$this->MissionDescriptions = array_slice($this->Strings, 4);
				} else {
//        	print_r(['Error in Battle Text LFD', $this]);
				}
    }

    public function __debugInfo()
    {
        return [
            'type' => $this->HeaderType,
            'name' => $this->HeaderName,
            'length' => $this->HeaderLength,
            'battlename' => $this->BattleName,
            'cutscenename' => $this->CutsceneName,
            'titlebattle1' => $this->TitleBattle1,
            'titlebattle2' => $this->TitleBattle2,
            'titecust1' => $this->TitleCutscene1,
            'titlesc2' => $this->TitleCutscene2,
            'deltane' => $this->DeltName,
            'systema' => $this->SystemName,
            'frame' => $this->Frame,
            'missionfs' => $this->MissionFilenames,
            'missiodns' => $this->MissionDescriptions
        ];
    }
}