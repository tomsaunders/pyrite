import { BriefingBase } from "./gen/briefing-base";

import { getInt, getShort } from "../hex";
import { IMission } from "../pyrite-base";
import { Event } from "./event";
import { Tag } from "./tag";
import { TIEString } from "./tie-string";

export class Briefing extends BriefingBase {
  public constructor(hex: ArrayBuffer, tie: IMission) {
    super(hex, tie);
    let offset = 0;
    this.RunningTime = getShort(hex, 0x000);
    this.Unknown = getShort(hex, 0x002);
    this.StartLength = getShort(hex, 0x004);
    this.EventsLength = getInt(hex, 0x006);

    this.Events = [];
    offset = 0x00a;
    let eventParsed = 0;
    while (eventParsed < this.EventsLength * 2) {
      const t = new Event(hex.slice(offset), this.TIE);
      t.Briefing = this;
      this.Events.push(t);
      offset += t.getLength();
      eventParsed += t.getLength();
    }

    this.Tags = [];
    offset = 0x32a;
    for (let i = 0; i < 32; i++) {
      const t = new Tag(hex.slice(offset), this.TIE);
      this.Tags.push(t);
      offset += t.getLength();
    }

    this.Strings = [];
    offset = offset;
    for (let i = 0; i < 32; i++) {
      const t = new TIEString(hex.slice(offset), this.TIE);
      this.Strings.push(t);
      offset += t.getLength();
    }
    this.BriefingLength = offset;
    this.afterConstruct();
  }
}
