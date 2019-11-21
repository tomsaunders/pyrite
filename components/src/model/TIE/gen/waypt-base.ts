import { getShort, writeShort } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class WayptBase extends PyriteBase implements Byteable {

  public static WAYPT_LENGTH = 0x1E;

  public StartPoints: number[];
  public Waypoints: number[];
  public Rendezvous: number;
  public Hyperspace: number;
  public Briefing: number;

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;

    this.StartPoints = [];
    offset = 0x00;
    for (let i = 0; i < 4; i++) {
      const t = getShort(hex, offset);
      this.StartPoints.push(t);
      offset += 8;
    }

    this.Waypoints = [];
    offset = 0x08;
    for (let i = 0; i < 8; i++) {
      const t = getShort(hex, offset);
      this.Waypoints.push(t);
      offset += 16;
    }
    this.Rendezvous = getShort(hex, 0x18);
    this.Hyperspace = getShort(hex, 0x1A);
    this.Briefing = getShort(hex, 0x1C);
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      StartPoints: this.StartPoints,
      Waypoints: this.Waypoints,
      Rendezvous: this.Rendezvous,
      Hyperspace: this.Hyperspace,
      Briefing: this.Briefing
    };
  }

  protected toHexString() {

    const hex = "";

    let offset = 0;

    offset = 0x00;
    for (let i = 0; i < 4; i++) {
      const t = this.StartPoints[i];
      writeShort(hex, this.StartPoints[i], offset);
      offset += 8;
    }

    offset = 0x08;
    for (let i = 0; i < 8; i++) {
      const t = this.Waypoints[i];
      writeShort(hex, this.Waypoints[i], offset);
      offset += 16;
    }
    writeShort(hex, this.Rendezvous, 0x18);
    writeShort(hex, this.Hyperspace, 0x1A);
    writeShort(hex, this.Briefing, 0x1C);
    return hex;
  }

  public getLength(): number {
    return WayptBase.WAYPT_LENGTH;
  }
}
