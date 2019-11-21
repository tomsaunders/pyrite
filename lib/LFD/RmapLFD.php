<?php
namespace Pyrite\LFD;

use Pyrite\Hex;

class RmapLFD extends LFD
{
    public $SubHeaders = [];
    public $Blocks = [];

    public function __construct($hex)
    {
        parent::__construct($hex);
        $off = 16;
        while ($off < $this->HeaderLength) {
            $this->SubHeaders[] = new LFD(substr($hex, $off, 16));
            $off += 16;
        }
    }

    public function __debugInfo()
    {
        return [
            'type' => $this->HeaderType,
            'name' => $this->HeaderName,
            'length' => $this->HeaderLength,
            'subheaders' => $this->SubHeaders,
            'blocks' => $this->Blocks
        ];
    }
}
