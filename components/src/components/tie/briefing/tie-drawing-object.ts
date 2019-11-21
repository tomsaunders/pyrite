import { Event } from "../../../model/TIE";
import { DrawingObject } from "../../../view-model/drawing-object";
import { TIEDrawMap } from "./tie-map";

export abstract class TIEDrawingObject extends DrawingObject {
  public get startTick(): number {
    return this.event.Time;
  }
  public constructor(protected map: TIEDrawMap, public event: Event) {
    super(map.ctx, map.font);
  }
}
