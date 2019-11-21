import { getChar, getShort, writeChar, writeShort } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class TagBase extends PyriteBase implements Byteable {

  public TagLength = 0;

  public Length: number;
  public Text: string;

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;
    this.Length = getShort(hex, 0x0);
    if (this.Length === 0) {
      this.afterConstruct();
      return;
    }
    this.Text = getChar(hex, 0x2, this.Length);
    this.TagLength = offset;
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      Length: this.Length,
      Text: this.Text
    };
  }

  protected toHexString() {

    const hex = "";

    let offset = 0;
    writeShort(hex, this.Length, 0x0);
    if (this.Length === 0) {
      return;
    }
    writeChar(hex, this.Text, 0x2, this.Length);
    return hex;
  }

  public getLength(): number {
    return this.TagLength;
  }
}
