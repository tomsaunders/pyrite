import { Constants } from "../constants";

import { getByte, writeByte } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class GoalFGBase extends PyriteBase implements Byteable {

  public static GOALFG_LENGTH = 0x2;

  public Condition: number;
  public GoalAmount: number;

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;
    this.Condition = getByte(hex, 0x0);
    this.GoalAmount = getByte(hex, 0x1);
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      Condition: this.ConditionLabel,
      GoalAmount: this.GoalAmountLabel
    };
  }

  public get ConditionLabel() {
    return Constants.CONDITION[this.Condition] || "Unknown";
  }

  public get GoalAmountLabel() {
    return Constants.GOALAMOUNT[this.GoalAmount] || "Unknown";
  }

  protected toHexString() {

    const hex = "";

    let offset = 0;
    writeByte(hex, this.Condition, 0x0);
    writeByte(hex, this.GoalAmount, 0x1);
    return hex;
  }

  public getLength(): number {
    return GoalFGBase.GOALFG_LENGTH;
  }
}
