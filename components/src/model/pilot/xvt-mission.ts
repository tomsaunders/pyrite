const medals = ["Gold", "Silver", "Bronze", "Nickel", "Copper", "Lead"];

export class XvTMission {
  constructor(
    public attempts: number,
    public wins: number,
    public losses: number,
    public bestScore: number,
    public bestTime: number,
    public bestTimeToo: number,
    public bestRating: number,
    public something: number,
    public other: number
  ) {}

  public get rating(): string {
    return medals[this.bestRating];
  }

  public get summary(): string {
    const complete = this.wins > 0;
    if (complete && this.bestScore === 0 && this.bestTime === 0) {
      return `Completed with unlimited waves`;
    } else if (complete) {
      return `${this.bestScore} - ${this.bestTime}:${this.bestTimeToo} (${this.rating})`;
    } else {
      return `Mission not completed`;
    }
  }
}
