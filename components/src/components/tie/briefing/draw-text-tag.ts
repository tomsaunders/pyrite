import { Constants, Event, FlightGroup } from "../../../model/TIE";
import { TIEDrawingObject } from "./tie-drawing-object";
import { TIEDrawMap } from "./tie-map";

export class DrawTextTag extends TIEDrawingObject {
  public text: string;
  public x: number;
  public y: number;
  protected colour: string;
  public constructor(map: TIEDrawMap, event: Event) {
    super(map, event);
    this.text = event.Text;
    this.x = event.Variables[1];
    this.y = event.Variables[2];
    this.colour = Constants.TEXTTAGCOLOR[event.Variables[3]].toLowerCase();
    console.log("text", this.text, this.colour);
  }

  public draw(tick: number): void {
    const [x, y] = this.map.translate([this.x, this.y]);
    this.drawText(this.text, x, y, this.colour, 1.8);
  }
}
