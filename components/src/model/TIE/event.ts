import { Briefing } from "./Briefing";
import { Constants } from "./constants";
import { EventBase } from "./gen/event-base";

export enum EventType {
  PageBreak = 3,
  TitleText = 4,
  CaptionText = 5,
  MoveMap = 6,
  ZoomMap = 7,
  ClearFGTags = 8,
  FGTag1 = 9,
  FGTag8 = 16,
  ClearTextTags = 17,
  TextTag1 = 18,
  TextTag8 = 25
}

export class Event extends EventBase {
  public static countData: object = {
    3: 0, // Page Break
    4: 1, // Title Text
    5: 1, // Caption Text
    6: 2, // Nove Map
    7: 2, // Zoom Map
    8: 0,
    9: 1, // FG Tagsz
    10: 1,
    11: 1,
    12: 1,
    13: 1,
    14: 1,
    15: 1,
    16: 1,
    17: 0, // Clear tags
    18: 4, // text tags # x y color
    19: 4,
    20: 4,
    21: 4,
    22: 4,
    23: 4,
    24: 4,
    25: 4,
    34: 0
  };

  public Briefing: Briefing;

  public toString(): string {
    let extra: string = "";

    if (this.EventType === 4 || this.EventType === 5) {
      // title or captions
      extra = this.Briefing.Strings[this.Variables[0]].Text;
    } else if (this.EventType >= 9 && this.EventType <= 16) {
      const fg = this.TIE.getFlightGroup(this.Variables[0]);
      extra = `FG lookup ${fg.toString()}`;
    } else if (this.EventType >= 18 && this.EventType <= 25) {
      const v = this.Variables;
      const t = this.Briefing.Tags[v[0]].Text;
      const col = Constants.TEXTTAGCOLOR[v[3]];

      extra = `${t} at ${v[1]},${v[2]} ${col}`;
    } else if (this.Variables.length) {
      extra = "Vars " + this.Variables.join(", ");
    }
    return `${this.EventTypeLabel} ${extra} @ ${this.Time}`;
  }

  public get Text(): string {
    if (this.EventType === 4 || this.EventType === 5) {
      return this.Briefing.Strings[this.Variables[0]].Text;
    } else if (this.EventType >= 18 && this.EventType <= 25) {
      return this.Briefing.Tags[this.Variables[0]].Text;
    } else {
      return "Unknown Text";
    }
  }

  protected VariableCount(): number {
    if (Event.countData.hasOwnProperty(this.EventType)) {
      return Event.countData[this.EventType];
    }
    throw new Error(`Event.VariableCount - Unknown for ${this.EventType}`);
  }
}
