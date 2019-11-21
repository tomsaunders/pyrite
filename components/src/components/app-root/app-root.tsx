import "@ionic/core";
import { Component, h, JSX, State } from "@stencil/core";

@Component({
  tag: "app-root",
  styleUrl: "app-root.css"
})
export class AppRoot {
  public render(): JSX.Element {
    return (
      <ion-app>
        <ion-router useHash={false}>
          <ion-route url="/" component="tac-battle-types" />
          <ion-route url="/battles/:plt/:sub" component="tac-battles" />
          <ion-route url="/battles/:plt/:sub/:nr" component="tac-battle" />
          <ion-route url="/battles/:plt/:sub/:nr/:name" component="pyrite-mission"></ion-route>
          <ion-route url="/scratch" component="pyrite-scratch" />
          <ion-route url="/resources" component="pyrite-resources" />
          <ion-route url="/resource/:name" component="pyrite-resource" />
          <ion-route url="/plt/tie" component="pyrite-tie-plt" componentProps={{ file: "" }} />
          <ion-route
            url="/plt/xvt"
            component="pyrite-xvt-plt"
            componentProps={{ file: "http://localhost:5555/api/pilotfile/41151Coremy0.plt", ehtc: true }}
          />
        </ion-router>
        <ion-nav />
      </ion-app>
    );
  }
}
