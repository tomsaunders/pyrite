import { Component, h, JSX, Prop, State } from "@stencil/core";
import { Mission } from "../../model/TIE/mission";

@Component({
  tag: "pyrite-resource",
  styleUrl: "resource.scss"
})
export class PyriteMission {
  @Prop() public name: string;

  public componentWillLoad(): void {
    fetch(`http://localhost:4444/pyrite/api/lfd.php?name=${this.name}`)
      .then((res: Response) => res.arrayBuffer())
      .then((value: ArrayBuffer) => {
        // this.tie = new Mission(value);
      });
  }

  public render(): JSX.Element {
    return [
      <ion-header>
        <ion-toolbar color="primary">
          <ion-buttons slot="start">
            <ion-back-button defaultHref="/" />
          </ion-buttons>
          <ion-title>{this.name}</ion-title>
        </ion-toolbar>
      </ion-header>,
      <ion-content class="ion-padding">
        <p>{this.name}</p>
      </ion-content>
    ];
  }
}
