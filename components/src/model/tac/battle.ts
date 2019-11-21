import { BattleSummary } from "./battle-summary";

export class Battle extends BattleSummary {
  constructor(
    public URL: string,
    public code: string,
    public nr: number,
    public name: string,
    public ratingAvg: string,
    public missions: number
  ) {
    super(URL, code, nr, name, ratingAvg, missions);
  }

  public get route(): string {
    const [plt, sub, nr] = this.code.replace(" ", "-").split("-");
    return `/battles/${plt}/${sub}/${nr}`;
  }

  public static clone(from: Battle): Battle {
    return new Battle(from.URL, from.code, from.nr, from.name, from.ratingAvg, from.missions);
  }
}
