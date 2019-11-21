import { Component, h, JSX, State } from "@stencil/core";

@Component({
  tag: "app-home",
  styleUrl: "app-home.css"
})
export class AppHome {
  @State() public battles: Map<string, string[]>;

  public componentWillLoad(): void {
    fetch("http://localhost:4444/pyrite/api/battles.php")
      .then((res: Response) => res.json())
      .then((json: any) => {
        this.battles = new Map<string, string[]>(Object.entries(json));
      });
  }

  public render(): JSX.Element {
    if (!this.battles) {
      return "";
    }

    let listItems: JSX.Element[] = [];
    this.battles.forEach((missions: string[], battle: string) => {
      listItems.push(
        <ion-list-header>
          <ion-label>{battle}</ion-label>
        </ion-list-header>
      );
      listItems = listItems.concat(
        missions.map((mission: string) => <ion-item href={`/mission/${mission}`}>{mission}</ion-item>)
      );
    });

    return [
      <ion-header>
        <ion-toolbar color="primary">
          <ion-title>Mission Select</ion-title>
        </ion-toolbar>
      </ion-header>,

      <ion-content class="ion-padding">
        <ion-list>{listItems}</ion-list>
      </ion-content>
    ];
  }
}
