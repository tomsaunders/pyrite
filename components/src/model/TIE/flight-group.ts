import { FlightGroupBase } from "./gen/flight-group-base";
import { Difficulty } from "./mission";
import { Craft } from "./craft";
import { GoalFG } from "./goal-fg";
import { Constants } from "./constants";

export class FlightGroup extends FlightGroupBase {
  public craft: Craft;

  public get label(): string {
    return this.toString();
  }

  public get summary(): string {
    return `${this.count} ${this.label} [${this.GroupAILabel}]`;
  }

  public get hasMothership(): boolean {
    return this.ArriveViaMothership || this.AlternateArriveViaMothership;
  }

  public get mothershipFG(): FlightGroup[] {
    const ms = [];
    if (this.ArriveViaMothership) {
      ms.push(this.TIE.getFlightGroup(this.ArrivalMothership));
    }
    if (this.AlternateArriveViaMothership) {
      ms.push(this.TIE.getFlightGroup(this.AlternateArrivalMothership));
    }
    return ms;
  }

  public get hasMultipleWaves(): boolean {
    return this.NumberOfWaves > 0;
  }

  public get IFFLabel(): string {
    return this.TIE.getIFF(this.Iff);
  }

  public get showOnBriefing(): boolean {
    const enabled = this.Waypoints[3];
    return !!enabled.Briefing;
  }

  public get startCoordinates(): number[] {
    return this.Waypoints.map((w) => (w.StartPoints[0] * 1.6) / 1000);
  }

  public get hyperCoordinates(): number[] {
    return this.Waypoints.map((w) => (w.Hyperspace * 1.6) / 1000);
  }

  public get briefingCoordinates(): number[] {
    return this.Waypoints.map((w) => w.Briefing);
  }

  public get CraftTypeAbbr(): string {
    return Constants.CRAFTABBR[this.CraftType];
  }

  public toString(): string {
    return `${this.CraftTypeAbbr} ${this.Name}`;
  }

  public pointValue(difficulty: Difficulty): number {
    if (this.isFriendly) {
      return -10000;
    }
    let diffMult = 1;
    if (difficulty === Difficulty.Easy) diffMult = 0.75;
    else if (difficulty === Difficulty.Hard) diffMult = 1.25;
    const collisions = 1.125;

    const perShip = this.craft.pointValue * diffMult * collisions;
    let points = this.count * perShip;

    const captureCount = this.captureCount;
    points += 4 * captureCount * perShip; // captured craft are worth 5x, so add 4x to the kill points for each capturable craft.
    return Math.ceil(points);
  }

  public get isFriendly(): boolean {
    if (this.PlayerCraft) {
      return true;
    } else if (this.Iff === 1) {
      // TODO constants enum stuff
      return true;
    }
    return false;
  }

  public get arrives(): boolean {
    return true; // TODO check trigger is sensible
  }

  public get count(): number {
    return this.NumberOfCraft * (this.NumberOfWaves + 1);
  }

  public isInDifficulty(difficulty: Difficulty): boolean {
    if (!this.arrives) {
      return false;
    }
    return this.ArrivalDifficultyLabel === "All" || this.ArrivalDifficultyLabel.includes(difficulty);
  }

  public get primaryGoal(): GoalFG {
    return this.FlightGroupGoals[0];
  }

  public get secondaryGoal(): GoalFG {
    return this.FlightGroupGoals[1];
  }

  public get bonusGoal(): GoalFG {
    return this.FlightGroupGoals[3];
  }

  public get capturable(): boolean {
    return !!this.FlightGroupGoals.find((goal: GoalFG) => goal.isCaptureGoal); // TODO check global goals
  }

  public get captureCount(): number {
    return Math.max(...this.FlightGroupGoals.map((goal: GoalFG) => goal.captureCount(this.count)));
  }

  public get destroyable(): boolean {
    return this.GroupAI !== 5 || !!this.FlightGroupGoals.find((goal: GoalFG) => goal.isInvincibleGoal); // TODO check global goals
  }

  protected afterConstruct(): void {
    this.craft = new Craft(this.CraftType);
  }
}
