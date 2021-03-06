import { Component, h, JSX, Prop, State } from "@stencil/core";
import {
  BattleSummary,
  KillSummary,
  MissionScore,
  TFR,
  TrainingSummary
} from "../../../model/pilot";

@Component({
  tag: "pyrite-tie-plt",
  styleUrl: "pilot-viewer.scss",
  shadow: true
})
export class TIEPilotViewer {
  @Prop() public file: string;

  @State() protected upload: File;
  @State() protected tfr: TFR;

  private get filename(): string {
    if (this.upload) {
      return this.upload.name;
    } else if (this.file) {
      return this.file.split("/").pop();
    }
    return "";
  }

  public componentWillLoad(): void {
    if (this.file) {
      fetch(this.file)
        .then((res: Response) => res.arrayBuffer())
        .then((value: ArrayBuffer) => {
          this.tfr = new TFR(value);
        });
    }
  }

  public render(): JSX.Element {
    let content: JSX.Element = <p>Select a file to view</p>;

    if (this.tfr) {
      content = (
        <ion-slides
          pager={true}
          options={{ initialSlide: 1 }}
          onIonSlideDidChange={this.slideChange.bind(this)}
        >
          <ion-slide>{this.renderPilotInformation()}</ion-slide>
          <ion-slide>{this.renderBattles()}</ion-slide>
          <ion-slide>{this.renderKills()}</ion-slide>
          <ion-slide>{this.renderTraining()}</ion-slide>
        </ion-slides>
      );
    }

    return [
      <ion-header>
        <ion-toolbar color="primary">
          <ion-title>Pyrite TFR Viewer</ion-title>
          <ion-buttons slot={this.file ? "none" : "end"}>
            <ion-button
              fill="outline"
              slot="end"
              onClick={this.getFile.bind(this)}
            >
              Upload
            </ion-button>
          </ion-buttons>
        </ion-toolbar>
      </ion-header>,
      <input
        type="file"
        id="pyriteTFRUpload"
        onChange={this.fileChange.bind(this)}
      />,
      <ion-content class="ion-padding ios">{content}</ion-content>
    ];
  }

  private getFile(): void {
    document.getElementById("pyriteTFRUpload").click();
  }

  private fileChange(event: any): void {
    const input = event.target as HTMLInputElement;
    if (input.files) {
      this.upload = input.files[0];
      const fr = new FileReader();
      fr.onloadend = () => {
        this.tfr = new TFR(fr.result as ArrayBuffer);
      };
      fr.readAsArrayBuffer(this.upload);
    } else {
      // do nothing
    }
  }

  private slideChange(): void {
    document.querySelector("ion-content").scrollToTop(500);
  }

  private renderPilotInformation(): JSX.Element {
    const fields: { [key: string]: string | number } = {
      Filename: this.filename,
      Difficulty: this.tfr.DifficultyLabel,
      Status: this.tfr.PilotStatusLabel,
      Rank: this.tfr.RankLabel,
      "Secret Order": this.tfr.SecretOrderLabel,
      Score: this.tfr.score,
      Skill: this.tfr.skillScore === -1 ? "Maximum" : this.tfr.skillScore,
      "Laser hits": this.tfr.LaserLabel,
      "Warhead hits": this.tfr.WarheadLabel,
      "Total kills": this.tfr.totalKills,
      "Total captures": this.tfr.totalCaptures,
      "Craft lost": this.tfr.craftLost
    };

    return (
      <ion-list>
        <ion-list-header>
          <ion-label>Pilot Information</ion-label>
        </ion-list-header>
        {Object.entries(fields).map((value: [string, string]) =>
          this.renderItem(value[0], value[1])
        )}
      </ion-list>
    );
  }

  private renderItem(
    key: string,
    value: string | number,
    className?: string
  ): JSX.Element {
    return (
      <ion-item class={className}>
        <ion-note slot="start" color="light" class="ion-padding-end">
          {key}
        </ion-note>
        <ion-label class="ion-text-right">{value}</ion-label>
      </ion-item>
    );
  }

  private renderMission(
    key: string,
    score: number,
    complete: boolean,
    secret?: boolean,
    bonus?: boolean
  ): JSX.Element {
    if (!complete && !score) {
      return "";
    }
    const icons: JSX.Element[] = [
      <ion-icon
        class={complete ? "complete" : "failed"}
        ariaLabel={complete ? "Mission complete" : "Mission failed"}
        name={complete ? "checkmark-circle" : "close"}
      ></ion-icon>
    ];
    if (secret) {
      icons.push(<ion-icon class="secret" name="checkmark-circle"></ion-icon>);
    }
    if (bonus) {
      icons.push(<ion-icon class="bonus" name="checkmark-circle"></ion-icon>);
    }
    return (
      <ion-item class="data">
        <ion-note slot="start" color="light" class="ion-padding-end">
          {key}
        </ion-note>
        <ion-label class="ion-text-right">{score}</ion-label>
        <span slot="end">{icons}</span>
      </ion-item>
    );
  }

  private renderBattles(): JSX.Element {
    return (
      <ion-list>
        <ion-list-header>
          <ion-label>Battles</ion-label>
        </ion-list-header>
        {this.tfr.BattleSummary.filter(
          (battle: BattleSummary) => battle.missions.length
        ).map((battle: BattleSummary, b: number) => (
          <ion-item-group>
            <ion-item-divider>
              <ion-label>Battle {b + 1}</ion-label>
              <ion-note slot="end">{battle.status}</ion-note>
            </ion-item-divider>
            {battle.missions.map((mission: MissionScore, m: number) =>
              this.renderMission(
                `Mission ${m + 1}`,
                mission.score,
                mission.completed,
                mission.secret,
                mission.bonus
              )
            )}
          </ion-item-group>
        ))}
      </ion-list>
    );
  }

  private renderKills(): JSX.Element {
    return (
      <ion-list>
        <ion-list-header>
          <ion-label>Player Battle Victories</ion-label>
        </ion-list-header>
        {this.tfr.BattleVictories.filter((bv: KillSummary) => bv.kills).map(
          (bv: KillSummary) => this.renderItem(bv.craftLabel, bv.kills, "data")
        )}
        <ion-item class="no-data">
          <ion-note>No kills recorded</ion-note>
        </ion-item>
      </ion-list>
    );
  }

  private renderTraining(): JSX.Element {
    return (
      <ion-list>
        <ion-list-header>
          <ion-label>Training</ion-label>
        </ion-list-header>
        {this.tfr.TrainingSummary.map((train: TrainingSummary) => (
          <ion-item-group>
            <ion-item-divider>
              <ion-label>{train.craftLabel}</ion-label>
            </ion-item-divider>
            {this.renderItem(
              "Obstacle Course",
              `${train.trainingScore} (Level ${train.trainingLevel})`
            )}
            {train.missions.map((mission: MissionScore, m: number) =>
              this.renderMission(
                `Mission ${m + 1}`,
                mission.score,
                mission.completed
              )
            )}
            <ion-item class="no-data">
              <ion-note>No training missions flown</ion-note>
            </ion-item>
          </ion-item-group>
        ))}
      </ion-list>
    );
  }
}
