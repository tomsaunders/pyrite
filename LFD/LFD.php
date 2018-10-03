<?php
namespace Pyrite\LFD;

use Pyrite\Byteable;
use Pyrite\HexDecoder;
use Pyrite\PyriteBase;

class LFD extends PyriteBase implements Byteable
{
    use HexDecoder;

    public $HeaderType;
    public $HeaderName;
    public $HeaderLength;

    public function __construct($hex)
    {
        $this->hex = $hex;

        $this->HeaderType = $this->getChar($hex, 4);
        $this->HeaderName = $this->getChar($hex, 8, 4);
        $this->HeaderLength = $this->getInt($hex, 12);
    }

    public function getLength()
    {
        return $this->HeaderLength;
    }

    public function __debugInfo()
    {
        return [
            'type' => $this->HeaderType,
            'name' => $this->HeaderName,
            'length' => $this->HeaderLength
        ];
    }
}