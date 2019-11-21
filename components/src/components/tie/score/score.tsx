import { Component, h, JSX, Prop, State, Listen } from "@stencil/core";
import { FlightGroup, Constants, Trigger } from "../../../model/TIE";
import { Mission, Difficulty } from "../../../model/TIE/mission";
import { SegmentChangeEventDetail } from "@ionic/core";

interface Score {
  label: string;
  value: number | string;
}

@Component({
  tag: "pyrite-tie-score",
  styleUrl: "score.scss",
  shadow: false
})
export class TIEScoreComponent {
  @Prop() public mission: Mission;
  @State() public difficulty: Difficulty;
  @State() public flightGroups: Score[];
  @State() public goals: Score[];
  @State() public playerCraft: string;
  @State() public total: number;

  public componentWillLoad() {
    this.difficulty = Difficulty.Hard;
    this.updateState();
  }

  protected fgColor(fg: FlightGroup): string {
    const colours = ["success", "danger", "primary", "tertiary"];
    return colours[fg.Iff];
  }

  private updateState(): void {
    const goalP = this.mission.goalPoints(this.difficulty);
    this.total = goalP;
    const primary: Score[] = [{ label: "Primary Goals", value: goalP }];
    const secondary: Score[] = [];
    const bonus: Score[] = [];

    const playerFG = this.mission.getPlayerCraft(this.difficulty);
    this.playerCraft = playerFG.summary;

    this.flightGroups = [];
    this.mission.FlightGroups.filter((fg: FlightGroup) => fg.isInDifficulty(this.difficulty)).forEach(
      (fg: FlightGroup) => {
        let p = fg.pointValue(this.difficulty);
        if (p > 0) {
          let fgStr = fg.summary;

          if (fg.capturable) {
            fgStr += ` ${fg.captureCount} capturable`;
          } else if (!fg.destroyable) {
            p = 0;
            fgStr += " invincible or mission critical";
          }
          this.total += p;
          this.flightGroups.push({
            label: fgStr,
            value: p
          });
        }

        if (fg.primaryGoal.isSet) {
          primary.push({ label: fg.primaryGoal.goalText(fg.label, fg.count), value: "" });
        }
        if (fg.secondaryGoal.isSet) {
          secondary.push({ label: fg.secondaryGoal.goalText(fg.label, fg.count), value: "" });
        }
        if (fg.bonusGoal.isSet) {
          const bp = fg.BonusGoalPoints * 50;
          this.total += bp;
          bonus.push({ label: fg.bonusGoal.goalText(fg.label, fg.count), value: bp });
        }
      }
    );
    // todo globals
    if (secondary.length) {
      secondary.unshift({ label: "Secondary Goals", value: goalP });
      this.total += goalP;
    }
    if (bonus.length) {
      bonus.unshift({ label: "Bonus Goals", value: 3100 });
      this.total += 3100;
    }

    this.goals = [...primary, ...secondary, ...bonus];
  }

  @Listen("ionChange")
  public difficultySelect(e: CustomEvent<SegmentChangeEventDetail>): void {
    this.difficulty = e.detail.value as Difficulty;
    this.updateState();
  }

  public render() {
    return [
      <ion-segment>
        <ion-segment-button value="Easy" checked={this.difficulty === Difficulty.Easy}>
          <ion-label>Easy</ion-label>
        </ion-segment-button>
        <ion-segment-button value="Medium" checked={this.difficulty === Difficulty.Medium}>
          <ion-label>Medium</ion-label>
        </ion-segment-button>
        <ion-segment-button value="Hard" checked={this.difficulty === Difficulty.Hard}>
          <ion-label>Hard</ion-label>
        </ion-segment-button>
      </ion-segment>,
      <ion-list>
        <ion-list-header>
          <ion-label>Player Craft</ion-label>
        </ion-list-header>
        <ion-item>
          <ion-label>{this.playerCraft}</ion-label>
        </ion-item>
        <ion-list-header>
          <ion-label>Goals</ion-label>
        </ion-list-header>
        {this.goals.map((score: Score) => (
          <ion-item>
            <ion-label>{score.label}</ion-label>
            <ion-note slot="end" color="primary">
              {score.value}
            </ion-note>
          </ion-item>
        ))}
        <ion-list-header>
          <ion-label>Targets</ion-label>
        </ion-list-header>
        {this.flightGroups.map((score: Score) => (
          <ion-item>
            <ion-label>{score.label}</ion-label>
            <ion-note slot="end" color="primary">
              {score.value}
            </ion-note>
          </ion-item>
        ))}
        <ion-list-header>
          <ion-label>Total</ion-label>
        </ion-list-header>
        <ion-item color="primary">
          <ion-label>{this.total}</ion-label>
        </ion-item>
      </ion-list>
    ];
  }
}
