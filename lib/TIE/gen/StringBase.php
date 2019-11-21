<?php
namespace Pyrite\TIE;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class StringBase implements Byteable
{
    use HexDecoder;

    private $StringLength = 0;

    /** @var SHORT */
    public $Length;
    /** @var CHAR */
    public $Text;

    public function __construct($hex)
    {
        $offset = 0;
        $this->Length = $this->getShort($hex, 0x0);

        $this->Text = [];
        $offset = 0x2;
        for ($i = 0; $i < $this->Length; $i++) {
            $t = $this->getChar($hex, $offset);
            $this->Text[] = $t;
            $offset += 1;
        }
        $this->StringLength = $offset;
    }

    private function toHexString()
    {
        $hex = '';

        $offset = 0;
        $this->writeShort($hex, $this->Length, 0x0);

        $offset = 0x2;
        for ($i = 0; $i < $this->Length; $i++) {
            $t = $this->Text[$i];
            $this->writeChar($hex, $this->Text[$i], $offset);
            $offset += 1;
        }
        return $hex;
    }

    public function getLength()
    {
        return $this->StringLength;
    }
}
