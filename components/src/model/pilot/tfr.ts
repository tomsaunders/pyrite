import { BattleSummary, KillSummary, MissionScore, PilotData } from ".";
import { getBool, getByte, getByteString, getInt, getShort } from "../../model/hex";
import { Constants } from "../TIE";

export enum PilotStatus {
  Alive = 0,
  Captured = 1,
  Killed = 2
}
export const StatusLabel = ["Alive", "Captured", "Killed"];

export enum Rank {
  Cadet = 0,
  Officer = 1,
  Lieutenant = 2,
  Captain = 3,
  Commander = 4,
  General = 5
}
export const RankLabel = ["Cadet", "Officer", "Lieutenant", "Captain", "Commander", "General"];

export enum Difficulty {
  Easy = 0,
  Medium = 1,
  Hard = 2
}
export const DifficultyLabel = ["Easy", "Medium", "Hard"];

export enum SecretOrder {
  None = 0,
  First = 1,
  SecondCircle = 2,
  ThirdCircle = 3,
  FourthCircle = 4,
  InnerCircle = 5,
  EmperorsHand = 6
}
export const SecretLabel = [
  "None",
  "First Circle",
  "Second Circle",
  "Third Circle",
  "Fourth Circle",
  "Inner Circle",
  "Emperor's Hand",
  "Emperor's Eyes",
  "Emperor's Voice",
  "Emperor's Reach"
];

export enum BattleStatus {
  None = 0,
  InProgress = 1,
  Incomplete = 2,
  Completed = 3
}
export const BattleLabel = ["N/A", "In Progress", "Incomplete", "Completed"];

export const CraftTypes = ["T/F", "T/I", "T/B", "T/A", "GUN", "T/D", "MIS"];
export const CraftLabels = [
  "TIE Fighter",
  "TIE Interceptor",
  "TIE Bomber",
  "TIE Advanced",
  "Assault Gunboat",
  "TIE Defender",
  "Missile Boat"
];

export class TFR implements PilotData {
  public status: PilotStatus;
  public rank: Rank;
  public difficulty: Difficulty;
  public score: number;
  public skillScore: number;
  public secretOrder: SecretOrder;
  public trainingScores: number[];
  public trainingLevels: number[];
  public combatScores: number[][];
  public combatCompletes: boolean[][];
  public battleStatuses: BattleStatus[];
  public lastMissions: any[];
  public secretObjectives: any[];
  public bonusObjectives: any[];
  public battleScores: number[][];
  public totalKills: number;
  public totalCaptures: number;
  public killsByType: number[];
  public lasersFired: number;
  public lasersHit: number;
  public warheadsFired: number;
  public warheadsHit: number;
  public craftLost: number;
  public persistence: any;
  // backup

  constructor(hex: ArrayBuffer) {
    let off = 0x0;
    this.status = getByte(hex, 0x001);
    this.rank = getByte(hex, 0x002);
    this.difficulty = getByte(hex, 0x003);
    this.score = getInt(hex, 0x004);
    this.skillScore = getShort(hex, 0x008);
    this.secretOrder = getByte(hex, 0x00a);
    this.trainingScores = CraftTypes.map((v, i) => getInt(hex, 0x02a + i * 4));
    this.trainingLevels = CraftTypes.map((v, i) => getByte(hex, 0x05a + i));
    this.combatScores = CraftTypes.map((v, b) => {
      const combatScores = [];
      for (let m = 0; m < 8; m++) {
        off = 0x088 + b * 32 + m * 4;
        combatScores.push(getInt(hex, off));
      }
      return combatScores;
    });
    this.combatCompletes = CraftTypes.map((v, b) => {
      const combatCompletes = [];
      for (let m = 0; m < 8; m++) {
        off = 0x208 + b * 8 + m;
        combatCompletes.push(getBool(hex, off));
      }
      return combatCompletes;
    });
    this.battleStatuses = [];
    for (let b = 0; b < 20; b++) {
      this.battleStatuses.push(getByte(hex, 0x269 + b));
    }
    this.lastMissions = [];
    for (let b = 0; b < 20; b++) {
      this.lastMissions.push(getByte(hex, 0x27d + b));
    }
    this.secretObjectives = [];
    this.persistence = hex.slice(0x291, 0x291 + 256);
    for (let b = 0; b < 20; b++) {
      this.secretObjectives.push(getByte(hex, 0x391 + b));
    }
    this.bonusObjectives = [];
    for (let b = 0; b < 20; b++) {
      this.bonusObjectives.push(getByte(hex, 0x3a5 + b));
    }
    this.battleScores = [];
    for (let b = 0; b < 20; b++) {
      off = 0x3da + b * 32;
      const battleScores = [];
      for (let m = 0; m < 8; m++) {
        off = 0x3da + b * 32 + m * 4;
        battleScores.push(getInt(hex, off));
      }
      this.battleScores.push(battleScores);
    }
    this.totalKills = getShort(hex, 0x65a);
    this.totalCaptures = getShort(hex, 0x65c);
    off = 0x660;
    this.killsByType = [];
    while (off <= 0x6e8) {
      this.killsByType.push(getShort(hex, off));
      off += 2;
    }
    this.lasersFired = getInt(hex, 0x774);
    this.lasersHit = getInt(hex, 0x778);
    this.warheadsFired = getShort(hex, 0x780);
    this.warheadsHit = getShort(hex, 0x782);
    this.craftLost = getShort(hex, 0x786);
  }

  public get KillsByCraftType(): object {
    const o = {};
    this.killsByType.forEach((k, i) => {
      const label = Constants.CRAFTTYPE[i + 1];
      if (k) {
        o[label] = k;
      }
    });
    return o;
  }

  public get PilotStatusLabel(): string {
    return StatusLabel[this.status];
  }

  public get RankLabel(): string {
    return RankLabel[this.rank];
  }

  public get DifficultyLabel(): string {
    return DifficultyLabel[this.difficulty];
  }

  public get SecretOrderLabel(): string {
    return SecretLabel[this.secretOrder];
  }

  public get BattleStatusLabel(): string[] {
    return this.battleStatuses.map((status) => BattleLabel[status]);
  }

  public get LaserLabel(): string {
    return this.shootInfo(this.lasersHit, this.lasersFired);
  }

  public get WarheadLabel(): string {
    return this.shootInfo(this.warheadsHit, this.warheadsFired);
  }

  public get BattleSummary(): BattleSummary[] {
    return this.battleScores.map((scores: number[], battle: number) => {
      const status = this.battleStatuses[battle];
      const last = this.lastMissions[battle];
      const secret = getByteString(this.secretObjectives[battle]);
      const bonus = getByteString(this.bonusObjectives[battle]);
      const bs: BattleSummary = {
        completed: status === BattleStatus.Completed,
        status: this.BattleStatusLabel[status],
        missions: scores.slice(0, last ? last : 0).map((score: number, m: number) => {
          const mission: MissionScore = {
            completed: true,
            score,
            secret: secret.charAt(m) === "1",
            bonus: bonus.charAt(m) === "1"
          };
          return mission;
        })
      };
      return bs;
    });
  }

  public get BattleVictories(): KillSummary[] {
    return this.killsByType.map((kills: number, i: number) => {
      const craftLabel = Constants.CRAFTTYPE[i + 1];
      return {
        craftLabel,
        kills
      } as KillSummary;
    });
  }

  public get TrainingSummary(): TrainingSummary[] {
    return CraftLabels.map((craft: string, idx: number) => {
      const summary: TrainingSummary = {
        craftLabel: craft,
        trainingLevel: this.trainingLevels[idx],
        trainingScore: this.trainingScores[idx],
        missions: this.combatCompletes[idx].map((complete: boolean, mission: number) => {
          const combatMission: MissionScore = {
            completed: complete,
            score: this.combatScores[idx][mission]
          };
          return combatMission;
        })
      };

      return summary;
    });
  }

  private shootInfo(hit: number, fired: number): string {
    const per = fired ? Math.floor((hit / fired) * 100) : 0;
    return `${hit} / ${fired} (${per} %)`;
  }
}

export interface TrainingSummary {
  craftLabel: string;
  trainingLevel: number;
  trainingScore: number;
  missions: MissionScore[];
}
