import { Component, h, JSX, Prop, State, Listen } from "@stencil/core";
import { FlightGroup, GoalFG, Order, Waypt } from "../../../model/TIE";
import { TabButtonClickEventDetail } from "@ionic/core";

enum Tabs {
  INFO = "info",
  ARR = "arrival",
  GOALS = "goals",
  WAY = "waypoints",
  ORDERS = "orders",
  OPT = "options",
  UNK = "unknown"
}

@Component({
  tag: "pyrite-tie-flightgroup",
  styleUrl: "flightgroup.scss",
  shadow: false
})
export class TIEFlightGroupComponent {
  @Prop() public flightGroup: FlightGroup;
  @State() public selectedTab: Tabs;

  @Listen("ionTabButtonClick")
  public tabClick(event: CustomEvent<TabButtonClickEventDetail>): void {
    this.selectedTab = event.detail.tab as Tabs;
    event.preventDefault();
    event.stopPropagation();
  }

  public componentWillLoad(): void {
    this.selectedTab = Tabs.INFO;
  }

  public render() {
    return (
      <ion-card color="medium">
        <ion-card-header>
          <ion-card-subtitle>{this.flightGroup.CraftTypeLabel}</ion-card-subtitle>
          <ion-card-title>{this.flightGroup.Name}</ion-card-title>
          <ion-tab-bar selectedTab={this.selectedTab}>
            <ion-tab-button tab={Tabs.INFO}>
              <ion-icon name="basketball"></ion-icon>
              <ion-label>Info</ion-label>
            </ion-tab-button>
            <ion-tab-button tab={Tabs.ARR}>
              <ion-icon name="grid"></ion-icon>
              <ion-label>Arrival/Departure</ion-label>
            </ion-tab-button>
            <ion-tab-button tab={Tabs.ORDERS}>
              <ion-icon name="chatboxes"></ion-icon>
              <ion-label>Orders</ion-label>
            </ion-tab-button>
            <ion-tab-button tab={Tabs.GOALS}>
              <ion-icon name="chatboxes"></ion-icon>
              <ion-label>Goals</ion-label>
            </ion-tab-button>
            <ion-tab-button tab={Tabs.WAY}>
              <ion-icon name="chatboxes"></ion-icon>
              <ion-label>Waypoints</ion-label>
            </ion-tab-button>
            <ion-tab-button tab={Tabs.OPT}>
              <ion-icon name="chatboxes"></ion-icon>
              <ion-label>Options</ion-label>
            </ion-tab-button>
          </ion-tab-bar>
        </ion-card-header>

        <ion-card-content>
          {this.renderCraftInfo()}
          {this.renderArrivals()}
          {this.renderOrders()}
          {this.renderGoals()}
          {this.renderWaypoints()}
          {this.renderOptions()}
        </ion-card-content>
      </ion-card>
    );
  }
  private renderCraftInfo(): JSX.Element {
    const fields: { [key: string]: string | number } = {
      Name: this.flightGroup.Name,
      "Craft Type": this.flightGroup.CraftTypeLabel,
      "Number Of Craft": this.flightGroup.NumberOfCraft,
      "Number Of Waves": this.flightGroup.NumberOfWaves,
      Status: this.flightGroup.StatusLabel,
      Warhead: this.flightGroup.WarheadLabel,
      Beam: this.flightGroup.BeamLabel,
      IFF: this.flightGroup.IFFLabel,
      AI: this.flightGroup.GroupAILabel,
      Difficulty: this.flightGroup.ArrivalDifficultyLabel,
      Player: this.flightGroup.PlayerCraft ? "Yes" : "No"
    };
    return (
      <ion-list class={this.tabClass(Tabs.INFO)}>
        <ion-list-header>
          <ion-label>Craft Information</ion-label>
        </ion-list-header>
        {Object.entries(fields).map((value: [string, string]) => this.renderItem(value[0], value[1]))}
      </ion-list>
    );
  }

  private renderArrivals() {
    const andOr = this.flightGroup.Arrival1OrArrival2 ? "Or" : "And";
    const arrival: { [key: string]: string | number } = {
      Difficulty: this.flightGroup.ArrivalDifficultyLabel,
      Delay: `${this.flightGroup.ArrivalDelayMinutes}:${this.flightGroup.ArrivalDelaySeconds}`,
      Via: this.flightGroup.ArriveViaMothership
        ? this.flightGroup.TIE.getFlightGroup(this.flightGroup.ArrivalMothership).toString()
        : "Hyperspace",
      "Or Via": this.flightGroup.AlternateArriveViaMothership
        ? this.flightGroup.TIE.getFlightGroup(this.flightGroup.AlternateArrivalMothership).toString()
        : "Hyperspace",
      When: this.flightGroup.Arrival1.toString()
    };
    arrival[`${andOr} When`] = this.flightGroup.Arrival2.toString();

    const departure: { [key: string]: string | number } = {
      "Leave after": `${this.flightGroup.DepartureDelayMinutes}:${this.flightGroup.DepartureDelatSeconds}`,
      Via: this.flightGroup.DepartViaMothership
        ? this.flightGroup.TIE.getFlightGroup(this.flightGroup.DepartureMothership).toString()
        : "Hyperspace",
      "Or Via": this.flightGroup.AlternateDepartViaMothership
        ? this.flightGroup.TIE.getFlightGroup(this.flightGroup.AlternateDepartureMothership).toString()
        : "Hyperspace",
      "Abort when": this.flightGroup.AbortTriggerLabel
    };

    if (arrival.Via === arrival["Or Via"]) {
      delete arrival["Or Via"];
    }
    if (departure.Via === departure["Or Via"]) {
      delete departure["Or Via"];
    }

    return (
      <ion-list class={this.tabClass(Tabs.ARR)}>
        <ion-list-header>
          <ion-label>Arrival</ion-label>
        </ion-list-header>
        {Object.entries(arrival).map((value: [string, string]) => this.renderItem(value[0], value[1]))}
        <ion-list-header>
          <ion-label>Departure</ion-label>
        </ion-list-header>
        {Object.entries(departure).map((value: [string, string]) => this.renderItem(value[0], value[1]))}
      </ion-list>
    );
  }

  private renderOrders() {
    return (
      <ion-list class={this.tabClass(Tabs.ORDERS)}>
        <ion-list-header>
          <ion-label>Orders</ion-label>
        </ion-list-header>
        {this.flightGroup.Orders.map((order: Order) => (
          <ion-item class={order.isSet ? "show" : "hide"}>
            <ion-label>{order.toString()}</ion-label>
          </ion-item>
        ))}
      </ion-list>
    );
  }

  private renderGoals() {
    const types = ["Primary", "Secondary", "Secret", "Bonus"];
    const points =
      this.flightGroup.BonusGoalPoints && this.flightGroup.FlightGroupGoals[3].isSet ? (
        <ion-item>
          <ion-note slot="start" color="light" class="ion-padding-end">
            Bonus Points
          </ion-note>
          <ion-label class="ion-text-right">{this.flightGroup.BonusGoalPoints * 50}</ion-label>
        </ion-item>
      ) : (
        ""
      );
    return (
      <ion-list class={this.tabClass(Tabs.GOALS)}>
        <ion-list-header>
          <ion-label>Goals</ion-label>
        </ion-list-header>
        {this.flightGroup.FlightGroupGoals.map((goal: GoalFG, idx: number) => (
          <ion-item class={goal.isSet ? "show" : "hide"}>
            <ion-note slot="start" color="light" class="ion-padding-end">
              {types[idx]}
            </ion-note>
            <ion-label class="ion-text-right">{goal.toString()}</ion-label>
          </ion-item>
        ))}
        {points}
      </ion-list>
    );
  }

  private renderWaypoints() {
    const startPoints = [1, 2, 3, 4];
    const wayPoints = [1, 2, 3, 4, 5, 6, 7, 8];
    const fgwp = this.flightGroup.Waypoints.slice(0, 3);
    const on = this.flightGroup.Waypoints[3];
    return (
      <div class={this.tabClass(Tabs.WAY)}>
        <h3>Waypoints</h3>
        <ion-grid>
          {startPoints.map((num, idx) => (
            <ion-row class={on.StartPoints[idx] ? "show" : "hide"}>
              <ion-col>Start Point #{num}</ion-col>
              {fgwp.map((way: Waypt) => (
                <ion-col>{way.StartPoints[idx] * 0.16}</ion-col>
              ))}
            </ion-row>
          ))}
          {wayPoints.map((num, idx) => (
            <ion-row class={on.Waypoints[idx] ? "show" : "hide"}>
              <ion-col>Way Point #{num}</ion-col>
              {fgwp.map((way: Waypt) => (
                <ion-col>{way.Waypoints[idx] * 0.16}</ion-col>
              ))}
            </ion-row>
          ))}
          <ion-row class={on.Rendezvous ? "show" : "hide"}>
            <ion-col>Rendezvous Point</ion-col>
            {fgwp.map((way: Waypt) => (
              <ion-col>{way.Rendezvous * 0.16}</ion-col>
            ))}
          </ion-row>
          <ion-row class={on.Hyperspace ? "show" : "hide"}>
            <ion-col>Hyperspace Point</ion-col>
            {fgwp.map((way: Waypt) => (
              <ion-col>{way.Hyperspace * 0.16}</ion-col>
            ))}
          </ion-row>
          <ion-row class={on.Briefing ? "show" : "hide"}>
            <ion-col>Briefing Point</ion-col>
            {fgwp.map((way: Waypt) => (
              <ion-col>{way.Briefing * 0.16}</ion-col>
            ))}
          </ion-row>
        </ion-grid>
      </div>
    );
  }

  private renderOptions(): JSX.Element {
    const fields: { [key: string]: string | number } = {
      Radio: this.flightGroup.ObeyPlayerOrders ? "Yes" : "No",
      Pilot: this.flightGroup.Pilot,
      Cargo: this.flightGroup.Cargo,
      "Special Cargo": this.flightGroup.SpecialCargo,
      "Special Cargo Craft": this.flightGroup.RandomSpecialCargoCraft ? "Random" : this.flightGroup.SpecialCargoCraft,
      Markings: this.flightGroup.MarkingsLabel,
      Formation: this.flightGroup.FormationLabel,
      "Formation Spacing": this.flightGroup.FormationSpacing,
      "Leader Spacing": this.flightGroup.LeaderSpacing,
      Yaw: this.flightGroup.Yaw,
      Pitch: this.flightGroup.Pitch,
      Roll: this.flightGroup.Roll,
      "Global Group": this.flightGroup.GlobalGroup
    };
    return (
      <ion-list class={this.tabClass(Tabs.OPT)}>
        <ion-list-header>
          <ion-label>Craft Options</ion-label>
        </ion-list-header>
        {Object.entries(fields).map((value: [string, string]) => this.renderItem(value[0], value[1]))}
      </ion-list>
    );
  }

  private tabClass(tab: Tabs): string {
    return this.selectedTab === tab ? "show" : "hide";
  }

  private renderItem(key: string, value: string | number, className?: string): JSX.Element {
    return (
      <ion-item class={className}>
        <ion-note slot="start" color="light" class="ion-padding-end">
          {key}
        </ion-note>
        <ion-label class="ion-text-right">{value}</ion-label>
      </ion-item>
    );
  }
}
