<?php
namespace Pyrite\TIE;

use Pyrite\Summary;

class Message extends MessageBase implements Summary
{
    public $messageColour = 0;

    protected function afterConstruct()
    {
        if (strlen($this->Message) && is_numeric($this->Message[0])) {
            $this->messageColour = (int) $this->Message[0];
        }
    }

    public function getMessageColourLabel()
    {
        return Constants::$MESSAGECOLOR[$this->messageColour];
    }

    public function __debugInfo()
    {
        $start = $this->messageColour !== 0;
        return [
            'Message' => substr($this->Message, $start),
            'MessageColour' => $this->getMessageColourLabel(),
            'Triggers' => $this->Triggers,
            'EditorNote' => $this->EditorNote,
            'Trigger1OrTrigger2' => $this->Trigger1OrTrigger2
        ];
    }

    public function summaryHash() {
		$start = $this->messageColour !== 0;
		foreach ($this->Triggers as $trig){
			$trig->TIE = $this->TIE;
		}
		$triggas = [(string)$this->Triggers[0]];
		$two = (string)$this->Triggers[1];
		if ($two !== 'Always') {
			$triggas[] = $this->Trigger1OrTrigger2 ? 'OR' : 'AND';
			$triggas[] = $two;
		}
		return [
			'Message' => substr($this->Message, $start),
			'MessageColour' => $this->getMessageColourLabel(),
			'Triggers' => implode("<br />", $triggas)
			];
	}
}
