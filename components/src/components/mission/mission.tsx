import { TabButtonClickEventDetail } from "@ionic/core";
import { Component, h, JSX, Listen, Prop, State } from "@stencil/core";
import { Mission } from "../../model/TIE/mission";

enum Tabs {
  ROOT = "root",
  FGS = "flightgroups",
  MSG = "messages",
  GOALS = "global-goals",
  BRIEF = "briefing",
  PREQ = "pre-questions",
  POST = "post-questions",
  SCORE = "score"
}

@Component({
  tag: "pyrite-mission",
  styleUrl: "mission.scss",
  shadow: true
})
export class PyriteMission {
  @Prop() public plt: string;
  @Prop() public sub: string;
  @Prop() public nr: string;
  @Prop() public name: string;
  @Prop() public file: string;

  @State() protected tie: Mission;
  @State() protected selectedTab: string;

  public componentWillLoad(): void {
    const url = this.file
      ? this.file
      : `http://localhost:5555/api/battle/${this.plt}/${this.sub}/${this.nr}/download/${this.name}`;
    fetch(url)
      .then((res: Response) => res.arrayBuffer())
      .then((value: ArrayBuffer) => {
        this.tie = new Mission(value);
        this.selectedTab = Tabs.FGS;
      });
  }

  @Listen("ionTabButtonClick")
  public tabClick(event: CustomEvent<TabButtonClickEventDetail>): void {
    this.selectedTab = event.detail.tab;
  }

  public render(): JSX.Element {
    if (!this.tie) {
      return "Loading";
    }

    return [
      <ion-header>
        <ion-toolbar color="tertiary">
          <ion-buttons slot="start">
            <ion-back-button defaultHref="/" />
          </ion-buttons>
          <ion-title>{this.name}</ion-title>
        </ion-toolbar>
      </ion-header>,
      <ion-content class="ion-padding">
        <ion-tab-bar selectedTab={this.selectedTab} color="tertiary">
          <ion-tab-button tab={Tabs.FGS}>
            <ion-icon name="basketball"></ion-icon>
            <ion-label>Flight Groups</ion-label>
          </ion-tab-button>
          <ion-tab-button tab={Tabs.BRIEF}>
            <ion-icon name="grid"></ion-icon>
            <ion-label>Briefing</ion-label>
          </ion-tab-button>
          <ion-tab-button tab={Tabs.PREQ}>
            <ion-icon name="chatboxes"></ion-icon>
            <ion-label>Questions</ion-label>
          </ion-tab-button>
          <ion-tab-button tab={Tabs.SCORE}>
            <ion-icon name="trophy"></ion-icon>
            <ion-label>Score</ion-label>
          </ion-tab-button>
        </ion-tab-bar>
        <pyrite-tie-flightgroups
          mission={this.tie}
          class={this.tabClass(Tabs.FGS)}
        />
        <pyrite-tie-briefing
          mission={this.tie}
          class={this.tabClass(Tabs.BRIEF)}
        />
        <pyrite-tie-pre-mission-questions
          mission={this.tie}
          class={this.tabClass(Tabs.PREQ)}
        />
        <pyrite-tie-score
          mission={this.tie}
          class={this.tabClass(Tabs.SCORE)}
        />
      </ion-content>
    ];
  }

  private tabClass(tab: string): string {
    return this.selectedTab === tab ? "show" : "hide";
  }
}
