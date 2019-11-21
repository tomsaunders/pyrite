import { Constants } from "../constants";

import { getShort, writeShort } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class EventBase extends PyriteBase implements Byteable {

  public EventLength = 0;

  public Time: number;
  public EventType: number;
  public Variables: number[];

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;
    this.Time = getShort(hex, 0x0);
    this.EventType = getShort(hex, 0x2);

    this.Variables = [];
    offset = 0x4;
    for (let i = 0; i < this.VariableCount(); i++) {
      const t = getShort(hex, offset);
      this.Variables.push(t);
      offset += 2;
    }
    this.EventLength = offset;
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      Time: this.Time,
      EventType: this.EventTypeLabel,
      Variables: this.Variables
    };
  }

  public get EventTypeLabel() {
    return Constants.EVENTTYPE[this.EventType] || "Unknown";
  }

  protected abstract VariableCount();

  protected toHexString() {

    const hex = "";

    let offset = 0;
    writeShort(hex, this.Time, 0x0);
    writeShort(hex, this.EventType, 0x2);

    offset = 0x4;
    for (let i = 0; i < this.VariableCount(); i++) {
      const t = this.Variables[i];
      writeShort(hex, this.Variables[i], offset);
      offset += 2;
    }
    return hex;
  }

  public getLength(): number {
    return this.EventLength;
  }
}
