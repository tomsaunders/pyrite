import { Trigger } from "../trigger";

import { getBool, writeBool, writeObject } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class GlobalGoalBase extends PyriteBase implements Byteable {

  public static GLOBALGOAL_LENGTH = 0x1C;

  public Triggers: Trigger[];
  public Trigger1OrTrigger2: boolean;

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;

    this.Triggers = [];
    offset = 0x00;
    for (let i = 0; i < 2; i++) {
      const t = new Trigger(hex.slice(offset), this.TIE)
;
      this.Triggers.push(t);
      offset += 0x4;
    }
    this.Trigger1OrTrigger2 = getBool(hex, 0x19);
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      Triggers: this.Triggers,
      Trigger1OrTrigger2: this.Trigger1OrTrigger2
    };
  }

  protected toHexString() {

    const hex = "";

    let offset = 0;

    offset = 0x00;
    for (let i = 0; i < 2; i++) {
      const t = this.Triggers[i];
      writeObject(hex, this.Triggers[i], offset);
      offset += 0x4;
    }
    writeBool(hex, this.Trigger1OrTrigger2, 0x19);
    return hex;
  }

  public getLength(): number {
    return GlobalGoalBase.GLOBALGOAL_LENGTH;
  }
}
