import { Component, h, JSX, State } from "@stencil/core";
import { BattleType } from "../../../model/tac/battle-type";

@Component({
  tag: "tac-battle-types",
  styleUrl: "battle-types.scss"
})
export class TACBattleTypesComponent {
  @State() public list: BattleType[];

  public componentWillLoad(): void {
    fetch("http://localhost:5555/api/battle-types")
      .then((res: Response) => res.json())
      .then((json: any) => {
        this.list = (json as BattleType[]).map((from) => BattleType.clone(from));
      });
  }

  public render(): JSX.Element {
    if (!this.list) {
      return "";
    }

    return [
      <ion-header>
        <ion-toolbar color="primary">
          <ion-title>Battle Types</ion-title>
        </ion-toolbar>
      </ion-header>,

      <ion-content class="ion-padding">
        <ion-list>
          {this.list.map((type) => (
            <ion-item href={type.route}>
              <ion-note slot="start">{type.code}</ion-note>
              <ion-label>
                {type.platform} - {type.subgroup}
              </ion-label>
              <ion-note slot="end">{type.count}</ion-note>
            </ion-item>
          ))}
        </ion-list>
      </ion-content>
    ];
  }
}
