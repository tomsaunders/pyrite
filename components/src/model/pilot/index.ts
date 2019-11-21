export { TFR, TrainingSummary } from "./tfr";
export { XvTPlt as PLT } from "./xvt";

export interface PilotData {
  LaserLabel: string;
  WarheadLabel: string;
  BattleSummary: BattleSummary[];
  BattleVictories: KillSummary[];
}

export interface BattleSummary {
  completed: boolean;
  status: string;
  missions: MissionScore[];
}

export interface KillSummary {
  craftLabel: string;
  kills: number;
}

export interface MissionScore {
  completed: boolean;
  score: number;
  secret?: boolean;
  bonus?: boolean;
}
