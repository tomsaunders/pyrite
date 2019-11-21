import { getByte, getByteString, getChar } from "../../hex";

export class Glyph {
  public data: string[] = [];
  public code: number;
  public char: string;
  public image: ImageData;

  constructor(hex: ArrayBuffer | undefined, public w: number, public h: number) {
    this.data = [];
    this.image = new ImageData(w, h);

    if (!hex) {
      return;
    }

    let off: number = 0;
    let i = 0;
    for (let y = 0; y < h; y++) {
      const char = getByte(hex, off);
      const bin = getByteString(char);
      const str: string = bin.substr(0, this.w);
      for (let x = 0; x < w; x++) {
        const on = str[x] === "1";
        this.image.data[i + 0] = on ? 255 : 0;
        this.image.data[i + 1] = on ? 128 : 0;
        this.image.data[i + 2] = on ? 128 : 0;
        this.image.data[i + 3] = 255;
        i += 4;
      }

      this.data.push(str);
      off += 2;
    }
  }

  public clone(): Glyph {
    const clone = new Glyph(undefined, this.w, this.h);
    clone.data = this.data;
    clone.image = new ImageData(this.image.data.slice(0), this.w, this.h);
    clone.code = this.code;
    clone.char = this.char;
    return clone;
  }

  public rgb(r: number, g: number, b: number): Glyph {
    const clone = this.clone();

    for (let i = 0; i < clone.image.data.length; i += 4) {
      const on = this.image.data[i] === 255;
      clone.image.data[i + 0] = on ? r : 0;
      clone.image.data[i + 1] = on ? g : 0;
      clone.image.data[i + 2] = on ? b : 0;
      clone.image.data[i + 3] = on ? 255 : 0;
    }
    return clone;
  }

  public toString(): string {
    return this.data.join("\n");
  }

  public get ImageBitmap(): ImageBitmap {
    const off = new OffscreenCanvas(this.w, this.h);
    const ctx: OffscreenCanvasRenderingContext2D = off.getContext("2d") as OffscreenCanvasRenderingContext2D;
    ctx.putImageData(this.image, 0, 0);

    return off.transferToImageBitmap();
  }

  public get startHighlight(): boolean {
    return this.char === "[";
  }

  public get endHighlight(): boolean {
    return this.char === "]";
  }
}
