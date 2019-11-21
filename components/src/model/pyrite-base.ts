import { Mission } from "./TIE";

export interface IMission {
  FlightGroups: IFlightGroup[];
  getFlightGroup(idx: number): IFlightGroup;
  getGlobalGroup(idx: number): IFlightGroup[];
  getIFF(idx: number): string;
}

export interface IFlightGroup {
  label: string;
}

export abstract class PyriteBase {
  public constructor(public hex: ArrayBuffer, public TIE: IMission) {}

  // public compareHex(other: string): boolean {
  //   return this.hex === other;
  // }

  protected beforeConstruct() {}

  protected afterConstruct() {}
}
