export class BattleType {
  constructor(
    public URL: string,
    public code: string,
    public platform: string,
    public subgroup: string,
    public count: number
  ) {}

  public get route(): string {
    const [plt, sub] = this.code.split("-");
    return `/battles/${plt}/${sub}`;
  }

  public static clone(from: BattleType): BattleType {
    return new BattleType(from.URL, from.code, from.platform, from.subgroup, from.count);
  }
}
