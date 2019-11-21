import { getByte, getUInt, getUShort } from "../../hex";

export class FontHeader {
  // Width of a character in pixels (for proportional fonts use max width)
  public w: number;
  // Height of a character in pixels
  public h: number;
  // a combination of FT_COLOR=1 | FT_PROPORTIONAL=2 | FT_KERNED=4
  public flags: number;
  // The baseline of the font (top pixel is #1).  Usually the same as ft_h
  public baseline: number;
  // The first character defined in the font
  public minChar: number;
  // The last character defined in the font
  public maxChar: number;
  // For proportional fonts this is usually 0.  For fixed fonts this is ft_w/8.
  public byteWidth: number;
  // Offset to raw font data from beginning of this struct
  public dataOffset: number;
  // Reserved - must be 0.  This is set by the Descent program at load-time.
  public chars: number;
  // If FT_PROPORTIONAL is set, then this is the offset to the width table.
  public widths: number;
  // If FT_KERNED is set, then this is the offset to the kerning table.
  public kernData: number;
  constructor(hex: ArrayBuffer) {
    this.w = getUShort(hex, 0);
    this.h = getUShort(hex, 0x2);
    this.flags = getUShort(hex, 0x4);
    this.baseline = getUShort(hex, 0x6);
    this.minChar = getByte(hex, 0x8);
    this.maxChar = getByte(hex, 0x9);
    this.byteWidth = getUShort(hex, 10);
    this.dataOffset = getUInt(hex, 12);
    this.chars = getUInt(hex, 16);
    this.widths = getUInt(hex, 20);
    this.kernData = getUInt(hex, 24);
  }
}
