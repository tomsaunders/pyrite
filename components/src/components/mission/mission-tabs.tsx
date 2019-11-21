import { Component, h, JSX, Prop, State } from "@stencil/core";
import { Mission } from "../../model/TIE/mission";

@Component({
  tag: "pyrite-mission-tabs"
})
export class PyriteMissionTabs {
  @State() protected tie: Mission;

  public render(): JSX.Element {
    return (
      <ion-tabs>
        <ion-tab tab="mission-root">
          <ion-header>
            <ion-toolbar color="primary">
              <ion-buttons slot="start">
                <ion-back-button defaultHref="/" />
              </ion-buttons>
              <ion-title>Mission</ion-title>
            </ion-toolbar>
          </ion-header>
          <ion-content class="ion-padding">
            <p>Root</p>
          </ion-content>
        </ion-tab>
        <ion-tab tab="mission-briefing">
          <p>Briefing</p>
        </ion-tab>
        <ion-tab tab="mission-pre-questions">
          <ion-header>
            <ion-toolbar color="primary">
              <ion-buttons slot="start">
                <ion-back-button defaultHref="/" />
              </ion-buttons>
              <ion-title>Questions</ion-title>
            </ion-toolbar>
          </ion-header>
          <ion-content class="ion-padding">
            <p>Questions</p>
            {/* <pyrite-tie-pre-mission-questions mission={this.tie}></pyrite-tie-pre-mission-questions> */}
          </ion-content>
        </ion-tab>
        <ion-tab-bar slot="top">
          <ion-tab-button tab="mission-root">
            <ion-icon name="basketball"></ion-icon>
          </ion-tab-button>
          <ion-tab-button tab="mission-briefing">
            <ion-icon name="grid"></ion-icon>
          </ion-tab-button>
          <ion-tab-button tab="mission-pre-questions">
            <ion-icon name="chatboxes"></ion-icon>
            <ion-badge>2</ion-badge>
          </ion-tab-button>
        </ion-tab-bar>
      </ion-tabs>
    );
  }
}
