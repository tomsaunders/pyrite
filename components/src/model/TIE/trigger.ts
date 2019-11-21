import { TriggerBase } from "./gen/trigger-base";

export class Trigger extends TriggerBase {
  public toString(): string {
    if (this.Condition === 0) {
      return "Always";
    }

    const parts = [this.TriggerAmountLabel, "of", this.VariableTypeLabel];
    if (this.VariableType === 1) {
      const fg = this.TIE.getFlightGroup(this.Variable);
      parts.push(fg + "");
    }
    parts.push("must");
    parts.push(this.ConditionLabel);

    return parts.join(" ");
  }
}
