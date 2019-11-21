import { Component, h, JSX, Prop, State } from "@stencil/core";
import { FlightGroup } from "../../../model/TIE";
import { Mission } from "../../../model/TIE/mission";

@Component({
  tag: "pyrite-tie-flightgroups",
  styleUrl: "flightgroups.scss",
  shadow: false
})
export class TIEFlightGroupsComponent {
  @Prop() public mission: Mission;
  @State() public flightGroups: FlightGroup[];
  @State() public selectedFG: FlightGroup;

  public componentWillLoad() {
    this.flightGroups = this.mission.FlightGroups;
    this.selectedFG = this.flightGroups[0];
  }

  protected selectFG(fg: FlightGroup): void {
    this.selectedFG = fg;
  }

  protected fgColor(fg: FlightGroup): string {
    if (fg === this.selectedFG) {
      return "light";
    }
    const colours = ["success", "danger", "primary", "tertiary", "secondary"];
    return colours[fg.Iff];
  }

  protected fgIcon(fg: FlightGroup) {
    if (fg.PlayerCraft) {
      return <ion-icon name="star"></ion-icon>;
    }
    return "";
  }

  public render() {
    return [
      <ion-list>
        {this.flightGroups.map((fg: FlightGroup) => (
          <ion-item onClick={this.selectFG.bind(this, fg)} color={this.fgColor(fg)}>
            <ion-label>{fg.toString()}</ion-label>
            {this.fgIcon(fg)}
          </ion-item>
        ))}
      </ion-list>,
      <pyrite-tie-flightgroup flightGroup={this.selectedFG} />
    ];
  }
}
