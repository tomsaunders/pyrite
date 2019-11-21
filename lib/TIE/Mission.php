<?php
namespace Pyrite\TIE;

class Mission extends MissionBase
{
    public $valid = false;
    protected function afterConstruct()
    {
        $this->valid = true;
    }

    public function lookupIFF($iff)
    {
        $IFFs = ["Rebel", "Imperial"];
        if (isset($this->TIE)) {
            $IFFs = array_merge($IFFs, $this->FileHeader->OtherIffNames);
        }

        $iffName = isset($IFFs[$iff]) && trim($IFFs[$iff]) ? $IFFs[$iff] : "Unknown IFF ({$iff})";

        if (is_numeric($iffName[0])) {
            $iffName = substr($iffName, 1) . ' (hostile)';
        }

        return $iffName;
    }

    public function lookupGlobalGroup($gg)
    {
        return 'TODO ' . $gg;
    }

    public static function validHex($hex){
    	$plat = substr($hex, 0, 2);
			$p = unpack('sshort', $plat)['short'];
    	return $p === FileHeader::PLATFORM_ID;
		}
}
