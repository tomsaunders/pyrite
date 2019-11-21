import { Constants } from "./constants";

const values = [
  0,
  600,
  400,
  800,
  800,
  400,
  600,
  600,
  1000,
  1600,
  400, //patch slot 10
  400, //patch slot 11
  1000,
  400,
  320,
  480,
  800,
  800,
  1600,
  2400,
  2400,
  600,
  960,
  1200,
  200,
  240,
  800,
  800,
  800,
  800,
  600,
  1200,
  1200,
  1600,
  1200,
  1600,
  2400,
  2000,
  2000,
  2200, //'Millenium Falcon/ slot 39',
  1600,
  2000,
  4400,
  4000,
  4000,
  4400,
  4000,
  4000,
  5000,
  6000,
  5000,
  5600,
  5000,
  8000,
  4000, //SSD,
  800,
  800,
  800,
  800,
  800,
  5200,
  5200,
  5200,
  5200,
  5200,
  5200,
  5200,
  5200,
  5200,
  5200,
  50,
  50,
  50,
  50,
  50,
  50,
  50,
  50,
  50,
  50, //gun emplacement
  50,
  50,
  50,
  50,
  50,
  50,
  0, //asteroid,
  0
]; //'Planet']

const missiles = [
  0, //"Unassigned",
  0, //"X-Wing",
  0, //"Y-Wing",
  0, //"A-Wing",
  0, //"B-Wing",
  4,
  6,
  8,
  8,
  8,
  0, //"Slot 10",
  0, //"Slot 11",
  80,
  0, //"T-Wing",
  0, //"Z-95 Headhunter",
  0, //"R-41 Starchaser",
  16
];

export class Craft {
  public constructor(private typeID: number) {}
  public get pointValue(): number {
    return values[this.typeID];
  }

  public get label(): string {
    return Constants.CRAFTTYPE[this.typeID];
  }

  public get isFighter(): boolean {
    return this.typeID < 25;
  }

  public get isStarship(): boolean {
    switch (this.label) {
      //TODO decide on the canonical source of names
      case "Corellian Corvette":
      case "Modified Corvette":
      case "Nebulon B Frigate":
      case "Modified Frigate":
      case "C-3 Passenger Liner":
      case "Carrack Cruiser":
      case "Strike Cruiser":
      case "Escort Carrier":
      case "Dreadnaught":
      case "Calamari Cruiser":
      case "Lt Calamari Cruiser":
      case "Interdictor Cruiser":
      case "Victory-class Star Destroyer":
      case "Victory Star Destroyer":
      case "Star Destroyer":
      case "Super Star Destroyer":
        return true;
    }
    return false;
  }

  public get missileCount(): number {
    return missiles[this.typeID];
  }
}
