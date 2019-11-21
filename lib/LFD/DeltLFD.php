<?php
namespace Pyrite\LFD;

use Pyrite\Byteable;
use Pyrite\Hex;
use Pyrite\HexDecoder;

class DeltLFD extends LFD
{
    public static $MAX_WIDTH = 640;
    public static $MAX_HEIGHT = 480;

    // TODO requires close reading of
    // https://github.com/MikeG621/LfdReader/blob/master/Delt.cs
    public $Left;
    public $Top;
    public $Right;
    public $Bottom;
    public $Rows = [];
    public $Reserved = 0x00;

    public function __construct($hex, $length)
    {
        parent::__construct($hex);
        //		Hex::render($hex);
        $hex = substr($hex, 16); // header
        $this->Left = $this->getShort($hex, 0);
        $this->Top = $this->getShort($hex, 2);
        $this->Right = $this->getShort($hex, 4);
        $this->Bottom = $this->getShort($hex, 6);
        $rest = substr($hex, 8, $length);

        while ($rest) {
            $row = new Row($rest);
            $rest = substr($rest, $row->getLength());
            $this->Rows[] = $row;
        }
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
            'type' => $this->HeaderType,
            'name' => $this->HeaderName,
            'length' => $this->HeaderLength,
            'left' => $this->Left,
            'top' => $this->Top,
            'right' => $this->Right,
            'bottom' => $this->Bottom,
            'width' => $this->width(),
            'height' => $this->height()
            //            'rows' => $this->Rows,
        ];
    }

    public function draw()
    {
        $image = imagecreate($this->width(), $this->height());
        $palette = json_decode(file_get_contents('../LFD/palette.json'), 1);
        foreach ($palette as $color) {
            imagecolorallocate($image, $color['rgb']['r'], $color['rgb']['g'], $color['rgb']['b']);
        }
        /** @var Row $row */
        foreach ($this->Rows as $row) {
            $row->paint($image);
        }
        return $image;
    }
}

// rows are read from the top down, left to right
class Row implements Byteable
{
    use HexDecoder;
    public $Length; // number of pixels defined in that row. # pixels = Length >> 1.
    public $Left; // used for 'broken' images where there are blank spots e.g. map has gap for officer's head. For images without blanks, this is usally = delt.left
    public $Top; // because of broken images, two rows can have the same top value
    public $ColorIndexes; // if (Length % 2 == 0) - uncompressed indexed values for the colour palette
    public $Operations; // else - operations array. continues until # pixels = length

    private $IsCompressed;
    private $NumPixels;

    public function __construct($hex)
    {
        $this->Length = $this->getShort($hex);
        $this->IsCompressed = $this->Length % 2 === 1;
        $this->NumPixels = $this->Length >> 1;

        if ($this->Length) {
            $this->Left = $this->getShort($hex, 2);
            $this->Top = $this->getShort($hex, 4);
            if ($this->IsCompressed) {
                // op code shit
                $this->Operations = new OpCode($hex);
            } else {
                $this->ColorIndexes = substr($hex, 6, $this->NumPixels);
            }
        }
    }

    public function getLength()
    {
        if ($this->Length === 0) {
            return 2;
        } elseif ($this->IsCompressed) {
            return $this->Length + 6;
        } else {
            return $this->NumPixels + 6;
        }
    }

    public function paint($image, $palette = null)
    {
        $y = $this->Top;
        for ($i = 0; $i < $this->NumPixels; $i++) {
            $x = $this->Left + $i;
            $color = $this->ColorIndexes[$i];
            imagesetpixel($image, $x, $y, $this->getByte($color));
        }
    }
}

class OpCode implements Byteable
{
    public $Value; // odd is repeat, even is read
    public $ColorIndexes; // if (Value & 1 == 0) - byte[value / 2]
    public $ColorIndex; // else (single byte)

    private $IsRepeat;

    public function getLength()
    {
        return $this->IsRepeat ? 2 : ($this->Value / 2 + 1);
    }
}
