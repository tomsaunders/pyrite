<?php
namespace Pyrite\TIE;

class Tag extends TagBase
{
    protected function afterConstruct()
    {
        $this->TagLength = $this->Length + 2;
    }

	public function __toString(){
		return $this->Text;
	}
}
