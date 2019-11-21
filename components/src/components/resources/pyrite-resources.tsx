import { Component, h, JSX, State } from "@stencil/core";

@Component({
  tag: "pyrite-resources",
  styleUrl: "pyrite-resources.scss"
})
export class PyriteResources {
  @State() public resources: Map<string, any>;

  public componentWillLoad(): void {
    fetch("http://localhost:4444/pyrite/api/lfds.php")
      .then((res: Response) => res.json())
      .then((json: any) => {
        this.resources = new Map<string, any>(Object.entries(json));
      });
  }

  public render(): JSX.Element {
    if (!this.resources) {
      return "";
    }

    const listItems: JSX.Element[] = [];
    this.resources.forEach((resource: any, name: string) => {
      listItems.push(<ion-item href={`/resource/${name}`}>{name}</ion-item>);
    });

    return [
      <ion-header>
        <ion-toolbar color="primary">
          <ion-title>Resource Select</ion-title>
        </ion-toolbar>
      </ion-header>,

      <ion-content class="ion-padding">
        <ion-list>{listItems}</ion-list>
      </ion-content>
    ];
  }
}
