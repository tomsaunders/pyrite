import { BattleSummary, KillSummary, PilotData } from ".";
import { getChar, getInt } from "../hex";
import { XvtTeamStats } from "./xvt-team-stats";

export class XvTPlt implements PilotData {
  public name: string;
  public totalScore: number;
  public kills: number;
  public lasersHit: number;
  public lasersFired: number;
  public warheadsHit: number;
  public warheadsFired: number;
  public craftLosses: number;

  public currentRating: string;

  public teamStats: XvtTeamStats[];

  constructor(hex: ArrayBuffer) {
    const off = 0x0;
    this.name = getChar(hex, off, 12);

    this.totalScore = getInt(hex, 0xe);
    this.lasersHit = getInt(hex, 0x143e);
    this.lasersFired = getInt(hex, 0x144a);
    this.warheadsHit = getInt(hex, 0x1456);
    this.warheadsFired = getInt(hex, 0x1462);
    this.currentRating = getChar(hex, 0x2392, 32);

    this.teamStats = [new XvtTeamStats("Rebel", hex.slice(0x3ef2)), new XvtTeamStats("Imperial", hex.slice(0x12716))];

    console.log(this);
  }

  public get LaserLabel(): string {
    return this.shootInfo(this.lasersHit, this.lasersFired);
  }

  public get WarheadLabel(): string {
    return this.shootInfo(this.warheadsHit, this.warheadsFired);
  }

  public get BattleSummary(): BattleSummary[] {
    return [];
  }

  public get BattleVictories(): KillSummary[] {
    return [];
  }

  private shootInfo(hit: number, fired: number): string {
    const per = fired ? Math.floor((hit / fired) * 100) : 0;
    return `${hit} / ${fired} (${per} %)`;
  }
}
