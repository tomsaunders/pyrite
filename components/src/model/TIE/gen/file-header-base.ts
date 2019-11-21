import { Constants } from "../constants";

import { getBool,
getByte,
getChar,
getShort,
writeBool,
writeByte,
writeChar,
writeShort } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class FileHeaderBase extends PyriteBase implements Byteable {

  public static FILEHEADER_LENGTH = 0x1CA;

  public PlatformID: number; // (-1)
  public NumFGs: number;
  public NumMessages: number;
  public Reserved: number; // (3) might be # of GlobalGoals
  public Unknown1: number;
  public Unknown2: boolean;
  public BriefingOfficers: number;
  public CapturedOnEject: boolean;
  public EndOfMissionMessages: string[];
  public OtherIffNames: string[];

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;
    this.PlatformID = getShort(hex, 0x000);
    this.NumFGs = getShort(hex, 0x002);
    this.NumMessages = getShort(hex, 0x004);
    this.Reserved = getShort(hex, 0x006);
    this.Unknown1 = getByte(hex, 0x008);
    this.Unknown2 = getBool(hex, 0x009);
    this.BriefingOfficers = getByte(hex, 0x00A);
    this.CapturedOnEject = getBool(hex, 0x00D);

    this.EndOfMissionMessages = [];
    offset = 0x018;
    for (let i = 0; i < 6; i++) {
      const t = getChar(hex, offset, 64);
      this.EndOfMissionMessages.push(t);
      offset += 64;
    }

    this.OtherIffNames = [];
    offset = 0x19A;
    for (let i = 0; i < 4; i++) {
      const t = getChar(hex, offset, 12);
      this.OtherIffNames.push(t);
      offset += 12;
    }
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      PlatformID: this.PlatformID,
      NumFGs: this.NumFGs,
      NumMessages: this.NumMessages,
      Reserved: this.Reserved,
      Unknown1: this.Unknown1,
      Unknown2: this.Unknown2,
      BriefingOfficers: this.BriefingOfficersLabel,
      CapturedOnEject: this.CapturedOnEject,
      EndOfMissionMessages: this.EndOfMissionMessages,
      OtherIffNames: this.OtherIffNames
    };
  }

  public get BriefingOfficersLabel() {
    return Constants.BRIEFINGOFFICERS[this.BriefingOfficers] || "Unknown";
  }

  protected toHexString() {

    const hex = "";

    let offset = 0;
    writeShort(hex, this.PlatformID, 0x000);
    writeShort(hex, this.NumFGs, 0x002);
    writeShort(hex, this.NumMessages, 0x004);
    writeShort(hex, this.Reserved, 0x006);
    writeByte(hex, this.Unknown1, 0x008);
    writeBool(hex, this.Unknown2, 0x009);
    writeByte(hex, this.BriefingOfficers, 0x00A);
    writeBool(hex, this.CapturedOnEject, 0x00D);

    offset = 0x018;
    for (let i = 0; i < 6; i++) {
      const t = this.EndOfMissionMessages[i];
      writeChar(hex, this.EndOfMissionMessages[i], offset, 64);
      offset += 64;
    }

    offset = 0x19A;
    for (let i = 0; i < 4; i++) {
      const t = this.OtherIffNames[i];
      writeChar(hex, this.OtherIffNames[i], offset, 12);
      offset += 12;
    }
    return hex;
  }

  public getLength(): number {
    return FileHeaderBase.FILEHEADER_LENGTH;
  }
}
