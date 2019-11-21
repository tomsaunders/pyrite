import { Component, h, JSX, State, Prop } from "@stencil/core";
import { Battle } from "../../../model/tac/battle";

@Component({
  tag: "tac-battle",
  styleUrl: "battle.scss"
})
export class TACBattleComponent {
  @Prop() public plt: string;
  @Prop() public sub: string;
  @Prop() public nr: string;
  @State() public battle: Battle;
  @State() public missions: string[] = [];

  public componentWillLoad(): void {
    fetch(`http://localhost:5555/api/battle/${this.plt}/${this.sub}/${this.nr}`)
      .then((res: Response) => res.json())
      .then((json: any) => {
        this.battle = Battle.clone(json as Battle);
      });
    fetch(`http://localhost:5555/api/battle/${this.plt}/${this.sub}/${this.nr}/download`)
      .then((res: Response) => res.json())
      .then((json: any) => {
        this.missions = json.missions;
      });
  }

  public render(): JSX.Element {
    if (!this.battle) {
      return "";
    }

    return [
      <ion-header>
        <ion-toolbar color="primary">
          <ion-title>Missions</ion-title>
        </ion-toolbar>
      </ion-header>,

      <ion-content class="ion-padding">
        <ion-list>
          {this.missions.map((mission) => (
            <ion-item href={`${this.battle.route}/${mission}`}>
              <ion-label>{mission}</ion-label>
            </ion-item>
          ))}
        </ion-list>
      </ion-content>
    ];
  }
}
