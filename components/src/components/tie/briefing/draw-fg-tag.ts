import { Constants, Event, FlightGroup } from "../../../model/TIE";
import { TIEDrawingObject } from "./tie-drawing-object";
import { TIEDrawMap } from "./tie-map";

export class DrawFGTag extends TIEDrawingObject {
  public fg: FlightGroup;
  protected colour: string;
  public constructor(map: TIEDrawMap, event: Event) {
    super(map, event);
    this.fg = map.mission.getFlightGroup(event.Variables[0]);
    this.colour = this.getHex(Constants.IFFCOLOR[this.fg.Iff].toLowerCase());
    console.log("FG", this.fg.toString(), this.colour);
  }

  public draw(tick: number): void {
    const [x, y] = this.map.translate(this.fg.briefingCoordinates);
    let size = 24;
    let off = 4;
    if (this.startTick) {
      // don't animate anything which is there at the start
      const animationRemaining = Math.max(0, this.startTick + 12 - tick);
      size += animationRemaining * 2;
      off += animationRemaining;
    }
    this.ctx.lineWidth = 3;
    this.ctx.shadowColor = "grey";
    this.ctx.shadowBlur = 5;
    this.ctx.strokeStyle = this.colour;
    this.ctx.strokeRect(x - off, y - off, size, size);
    this.ctx.lineWidth = 1;
    this.ctx.shadowColor = "none";
    this.ctx.shadowBlur = 0;
  }
}
