import { GoalFGBase } from "./gen/goal-fg-base";

export class GoalFG extends GoalFGBase {
  public toString(): string {
    return `${this.ConditionLabel} ${this.GoalAmountLabel}`;
  }

  public goalText(fgLabel: string, count: number): string {
    const amount = count === 1 && this.GoalAmount === 0 ? "" : `${this.GoalAmountLabel} of `;
    return `${amount}${fgLabel} must be ${this.ConditionLabel.toLowerCase()}`;
  }

  public get isSet(): boolean {
    return this.Condition !== 0 && this.Condition !== 10;
  }

  public get isCaptureGoal(): boolean {
    return this.Condition === 4; // TODO enum lookup
  }

  public captureCount(fgCount: number): number {
    if (!this.isCaptureGoal) {
      return 0;
    }
    switch (this.GoalAmount) {
      case 0: // 100%
        return fgCount;
      case 1: // 50%
        return Math.round(fgCount * 0.5);
      case 2: // at least one
      case 4: // special craft
        return 1;
      case 3: // all but one
        return fgCount - 1;
    }
    return 0;
  }

  public get isInvincibleGoal(): boolean {
    return [4, 9, 12, 13].includes(this.Condition);
  }
}
