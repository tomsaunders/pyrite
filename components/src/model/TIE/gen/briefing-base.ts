import { Event } from "../event";
import { Tag } from "../tag";
import { TIEString } from "../tie-string";

import { getInt, getShort, writeInt, writeObject, writeShort } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class BriefingBase extends PyriteBase implements Byteable {

  public BriefingLength = 0;

  public RunningTime: number;
  public Unknown: number;
  public StartLength: number;
  public EventsLength: number; // Number of shorts used for events.
  public Events: Event[]; // Set to 0 and impossible to generate in the same way, needs custom implementation
  public Tags: Tag[];
  public Strings: TIEString[];

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;
    this.RunningTime = getShort(hex, 0x000);
    this.Unknown = getShort(hex, 0x002);
    this.StartLength = getShort(hex, 0x004);
    this.EventsLength = getInt(hex, 0x006);

    this.Events = [];
    offset = 0x00A;
    for (let i = 0; i < 0; i++) {
      const t = new Event(hex.slice(offset), this.TIE)
;
      this.Events.push(t);
      offset += t.getLength();
    }

    this.Tags = [];
    offset = 0x32A;
    for (let i = 0; i < 32; i++) {
      const t = new Tag(hex.slice(offset), this.TIE)
;
      this.Tags.push(t);
      offset += t.getLength();
    }

    this.Strings = [];
    offset = offset;
    for (let i = 0; i < 32; i++) {
      const t = new TIEString(hex.slice(offset), this.TIE)
;
      this.Strings.push(t);
      offset += t.getLength();
    }
    this.BriefingLength = offset;
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      RunningTime: this.RunningTime,
      Unknown: this.Unknown,
      StartLength: this.StartLength,
      EventsLength: this.EventsLength,
      Events: this.Events,
      Tags: this.Tags,
      Strings: this.Strings
    };
  }

  protected toHexString() {

    const hex = "";

    let offset = 0;
    writeShort(hex, this.RunningTime, 0x000);
    writeShort(hex, this.Unknown, 0x002);
    writeShort(hex, this.StartLength, 0x004);
    writeInt(hex, this.EventsLength, 0x006);

    offset = 0x00A;
    for (let i = 0; i < 0; i++) {
      const t = this.Events[i];
      writeObject(hex, this.Events[i], offset);
      offset += t.getLength();
    }

    offset = 0x32A;
    for (let i = 0; i < 32; i++) {
      const t = this.Tags[i];
      writeObject(hex, this.Tags[i], offset);
      offset += t.getLength();
    }

    offset = offset;
    for (let i = 0; i < 32; i++) {
      const t = this.Strings[i];
      writeObject(hex, this.Strings[i], offset);
      offset += t.getLength();
    }
    return hex;
  }

  public getLength(): number {
    return this.BriefingLength;
  }
}
