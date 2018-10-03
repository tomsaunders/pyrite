<?php
namespace Pyrite\LFD;

use Pyrite\Byteable;
use Pyrite\HexDecoder;

class DeltLFD extends LFD
{
    // TODO requires close reading of 
    // https://github.com/MikeG621/LfdReader/blob/master/Delt.cs
    public $Left;
    public $Top;
    public $Right;
    public $Bottom;
    public $Rows = [];
    public $Reserved = 0x00;

    public function __construct($hex)
    {
        // parent::__construct($hex);
        $this->Left = $this->getShort($hex, 0);
        $this->Top = $this->getShort($hex, 2);
        $this->Right = $this->getShort($hex, 4);
        $this->Bottom = $this->getShort($hex, 6);
        $off = 8;

    }

    public function width()
    {
        return $this->Right - $this->Left + 1;
    }

    public function height()
    {
        return $this->Bottom - $this->Top + 1;
    }

    public function __debugInfo()
    {
        return [
            // 'type' => $this->HeaderType,
            // 'name' => $this->HeaderName,
            // 'length' => $this->HeaderLength,
            'left' => $this->Left,
            'top' => $this->Top,
            'right' => $this->Right,
            'bottom' => $this->Bottom,
            'width' => $this->width(),
            'height' => $this->height(),
            'rows' => $this->Rows
        ];
    }
}

class Row implements Byteable
{
    use HexDecoder;
    public $Length;
    public $Left;
    public $Top;
    public $ColorIndexes;
    public $Operations;

    public function __construct($hex)
    {

    }

    public function getLength()
    {
        return $this->Length;
    }
}

class OpCode
{
    public $Value;
    public $ColorIndexes;
    public $ColorIndex;
}