import { Component, h, JSX, State, Prop } from "@stencil/core";
import { BattleSummary } from "../../../model/tac/battle-summary";

@Component({
  tag: "tac-battles",
  styleUrl: "battles.scss"
})
export class TACBattlesComponent {
  @Prop() public plt: string;
  @Prop() public sub: string;
  @State() public list: BattleSummary[];

  public componentWillLoad(): void {
    fetch(`http://localhost:5555/api/battles/${this.plt}/${this.sub}`)
      .then((res: Response) => res.json())
      .then((json: any) => {
        this.list = (json as BattleSummary[]).map((from) => BattleSummary.clone(from));
      });
  }

  public render(): JSX.Element {
    if (!this.list) {
      return "";
    }

    return [
      <ion-header>
        <ion-toolbar color="primary">
          <ion-title>Battles</ion-title>
        </ion-toolbar>
      </ion-header>,

      <ion-content class="ion-padding">
        <ion-list>
          {this.list.map((type) => (
            <ion-item href={type.route}>
              <ion-note slot="start">{type.code}</ion-note>
              <ion-label>
                <h3>{type.name}</h3>
                <p>{type.missions} missions</p>
              </ion-label>
              <ion-note slot="end">
                <ion-badge>
                  {type.ratingAvg} <ion-icon name="star"></ion-icon>
                </ion-badge>
              </ion-note>
            </ion-item>
          ))}
        </ion-list>
      </ion-content>
    ];
  }
}
