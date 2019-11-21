import { FontFile, Glyph } from "../model/util/font";

export abstract class DrawingObject {
  constructor(public ctx: CanvasRenderingContext2D, public font: FontFile) {}
  public abstract draw(tick: number): void;
  protected rect(x: number, y: number, width: number, height: number, fill: string) {
    this.ctx.clearRect(x, y, width, height);
    this.ctx.fillStyle = fill;
    this.ctx.fillRect(x, y, width, height);
  }

  protected drawText(text: string, firstX: number, firstY: number, colourName?: string, scale: number = 2.5): void {
    const yellow = [255, 255, 85];
    const white = [251, 251, 251];
    const highlight = [0, 170, 0];

    let color = white;
    let sequence = this.font.getGlyphs(text);

    if (colourName) {
      color = this.getColour(colourName);
    }

    // work out the width of the text and whether it needs splitting
    let split: Glyph;
    let lastSpace: Glyph;
    let width = 0;
    for (const g of sequence) {
      width += g.w * scale;
      if (width > this.ctx.canvas.width + 10 && !split) {
        split = lastSpace;
      } else if (g.char === " ") {
        lastSpace = g;
      }
    }

    if (text[0] === ">") {
      // special title mode
      firstX = (this.ctx.canvas.width - width) / 2;
      color = yellow;
      sequence = sequence.slice(1);
    }

    this.ctx.scale(scale, scale);
    let x = firstX / scale;
    let y = firstY / scale;
    for (const g of sequence) {
      if (g.startHighlight) {
        color = highlight;
      } else if (g.endHighlight) {
        color = white;
      } else {
        const draw: Glyph = g.rgb.apply(g, color);

        this.ctx.drawImage(draw.ImageBitmap, x, y, g.w, g.h);
        x += g.w;
        if (g === split) {
          x = firstX;
          y += g.h;
        }
      }
    }
    this.ctx.scale(1 / scale, 1 / scale);
  }

  protected getColour(name: string): [number, number, number] {
    const lookup = {
      "green": [0, 170, 0],
      "red": [174, 0, 0],
      "purple": [255, 0, 255],
      "blue": [0, 121, 227],
      "light red": [255, 160, 122],
      "gray": [128, 128, 128],
      "white": [255, 255, 255]
    };
    if (lookup[name]) {
      return lookup[name];
    } else {
      return [0, 255, 255];
    }
  }

  protected getHex(name: string): string {
    const lookup = {
      "green": "#00aa00",
      "red": "#ae0000",
      "purple": "#FF00FF",
      "blue": "#0079E3",
      "light red": "#FFA07A",
      "gray": "#808080",
      "white": "#FFFFFF"
    };
    if (lookup[name]) {
      return lookup[name];
    } else {
      return "#00FFFF";
    }
  }
}
