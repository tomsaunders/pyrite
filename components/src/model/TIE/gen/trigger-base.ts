import { Constants } from "../constants";

import { getByte, writeByte } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class TriggerBase extends PyriteBase implements Byteable {

  public static TRIGGER_LENGTH = 0x4;

  public Condition: number;
  public VariableType: number;
  public Variable: number;
  public TriggerAmount: number;

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;
    this.Condition = getByte(hex, 0x0);
    this.VariableType = getByte(hex, 0x1);
    this.Variable = getByte(hex, 0x2);
    this.TriggerAmount = getByte(hex, 0x3);
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      Condition: this.ConditionLabel,
      VariableType: this.VariableTypeLabel,
      Variable: this.Variable,
      TriggerAmount: this.TriggerAmountLabel
    };
  }

  public get ConditionLabel() {
    return Constants.CONDITION[this.Condition] || "Unknown";
  }

  public get VariableTypeLabel() {
    return Constants.VARIABLETYPE[this.VariableType] || "Unknown";
  }

  public get TriggerAmountLabel() {
    return Constants.TRIGGERAMOUNT[this.TriggerAmount] || "Unknown";
  }

  protected toHexString() {

    const hex = "";

    let offset = 0;
    writeByte(hex, this.Condition, 0x0);
    writeByte(hex, this.VariableType, 0x1);
    writeByte(hex, this.Variable, 0x2);
    writeByte(hex, this.TriggerAmount, 0x3);
    return hex;
  }

  public getLength(): number {
    return TriggerBase.TRIGGER_LENGTH;
  }
}
