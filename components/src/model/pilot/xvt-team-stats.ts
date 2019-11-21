import { getInt, getIntArray } from "../hex";
import { XvTMission } from "./xvt-mission";

enum MedalTypes {
  Gold = 0,
  Silver = 1,
  Bronze = 2,
  Thing = 3,
  Thingo = 4,
  Lead = 5
}
const medals = ["Gold", "Silver", "Bronze", "Nickel", "Copper", "Lead"];
const modes = ["Training", "Melee", "Combat"];
const modeCount = 3;
const shipCount = 88;
const missionCount = 30;

export class XvtTeamStats {
  public meleeMedals: number[];
  public tournamentMedals: number[];
  public missionTopRatings: number[];
  public missionMedals: number[];
  public playCounts: number[];
  public kills: number[];

  public exerciseKillsByType: number[];
  public meleeKillsByType: number[];
  public combatKillsByType: number[];
  public exercisePartialsByType: number[];
  public meleePartialsByType: number[];
  public combatPartialsByType: number[];
  public exerciseAssistsByType: number[];
  public meleeAssistsByType: number[];
  public combatAssistsByType: number[];

  public hiddenCargoFound: number[];
  public lasersHit: number[];
  public lasersFired: number[];
  public warheadsHit: number[];
  public warheadsFired: number[];
  public craftLosses: number[];
  public collisionLosses: number[];
  public starshipLosses: number[];
  public mineLosses: number[];
  public missions: XvTMission[];

  constructor(public name: string, hex: ArrayBuffer) {
    this.meleeMedals = getIntArray(hex, 0x0, 6);
    this.tournamentMedals = getIntArray(hex, 0x18, 6);
    this.missionTopRatings = getIntArray(hex, 0x30, 6);
    this.missionMedals = getIntArray(hex, 0x48, 6);
    this.playCounts = getIntArray(hex, 0x90, 3);
    this.kills = getIntArray(hex, 0xa8, 3);

    this.exerciseKillsByType = getIntArray(hex, 0xc0, shipCount);
    this.meleeKillsByType = getIntArray(hex, 0x220, shipCount);
    this.combatKillsByType = getIntArray(hex, 0x380, shipCount);
    this.exercisePartialsByType = getIntArray(hex, 0x4e0, shipCount);
    this.meleePartialsByType = getIntArray(hex, 0x640, shipCount);
    this.combatPartialsByType = getIntArray(hex, 0x7a0, shipCount);
    this.exerciseAssistsByType = getIntArray(hex, 0x900, shipCount);
    this.meleeAssistsByType = getIntArray(hex, 0xa60, shipCount);
    this.combatAssistsByType = getIntArray(hex, 0xbc0, shipCount);

    this.hiddenCargoFound = getIntArray(hex, 0x117c, modeCount);
    this.lasersHit = getIntArray(hex, 0x1188, modeCount);
    this.lasersFired = getIntArray(hex, 0x1194, modeCount);
    this.warheadsHit = getIntArray(hex, 0x11a0, modeCount);
    this.warheadsFired = getIntArray(hex, 0x11ac, modeCount);
    this.craftLosses = getIntArray(hex, 0x11b8, modeCount);
    this.collisionLosses = getIntArray(hex, 0x11c4, modeCount);
    this.starshipLosses = getIntArray(hex, 0x11d0, modeCount);
    this.mineLosses = getIntArray(hex, 0x11dc, modeCount);

    const off = 0x1360;
    this.missions = [];
    for (let i = 0; i < missionCount; i++) {
      const miss: number[] = getIntArray(hex, off + i * 36, 9);
      this.missions.push(
        new XvTMission(miss[0], miss[1], miss[2], miss[3], miss[4], miss[5], miss[6], miss[7], miss[8])
      );
    }
  }
}
