import { getByte, getSChar, getUInt } from "../../hex";
import { Glyph } from "./glyph";

// http://www.descent2.com/ddn/specs/fnt/
export class FontFile {
  public h: number;
  public glyphs: Glyph[] = [];

  constructor(hex: ArrayBuffer) {
    // worry about read length 4 later
    let off: number = 1;
    this.h = getByte(hex, off); // all glyphs will have this height. width can vary.

    let count = 0;
    for (count; off < hex.byteLength; count++) {
      off += this.h * 2 + 2;
    }
    off = 0;
    for (let i = 0; i < count; i++) {
      const w = getByte(hex, off);
      off += 2;

      this.glyphs.push(new Glyph(hex.slice(off), w, this.h));
      this.glyphs.push(new Glyph(hex.slice(off + 1), w, this.h));
      off += this.h * 2;
    }
  }

  public getGlyphs(text: string): Glyph[] {
    const normal = this.normalGlyphs;
    const sequence: Glyph[] = [];
    for (const char of text) {
      const code = char.charCodeAt(0);
      if (!code) {
        continue;
      }
      const g = normal[code - 32];
      if (g) {
        g.code = code;
        g.char = char;
        sequence.push(g.clone());
      }
    }

    return sequence;
  }

  public get normalGlyphs(): Glyph[] {
    return this.glyphs.filter((g, i) => i % 2 === 0);
  }

  public get invertedGlyphs(): Glyph[] {
    return this.glyphs.filter((g, i) => i % 2 === 1);
  }
}
