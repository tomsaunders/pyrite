export class BattleSummary {
  constructor(
    public URL: string,
    public code: string,
    public nr: number,
    public name: string,
    public ratingAvg: string,
    public missions: number
  ) {}

  public get route(): string {
    const [plt, sub, nr] = this.code.replace(" ", "-").split("-");
    return `/battles/${plt}/${sub}/${nr}`;
  }

  public static clone(from: BattleSummary): BattleSummary {
    return new BattleSummary(from.URL, from.code, from.nr, from.name, from.ratingAvg, from.missions);
  }
}
