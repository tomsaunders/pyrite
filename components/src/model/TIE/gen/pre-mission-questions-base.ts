import { getByte,
getChar,
getShort,
writeByte,
writeChar,
writeShort } from "../../hex";

import { Byteable } from "../../byteable";
import { IMission, PyriteBase } from "../../pyrite-base";
// tslint:disable member-ordering
// tslint:disable prefer-const

export abstract class PreMissionQuestionsBase extends PyriteBase implements Byteable {

  public PreMissionQuestionsLength = 0;

  public Length: number;
  public Question: string;
  public Reserved: number; // (0xA)
  public Answer: string;

  public constructor(hex: ArrayBuffer, tie?: IMission) {
    super(hex, tie);
    this.beforeConstruct();
    let offset = 0;
    this.Length = getShort(hex, 0x0);
    if (this.Length === 0) {
      this.afterConstruct();
      return;
    }
    this.Question = getChar(hex, 0x2, this.QuestionLength());
    offset = 0x2;
    offset += this.QuestionLength();
    this.Reserved = getByte(hex, offset);
    offset += 1;
    this.Answer = getChar(hex, offset, this.AnswerLength());
    offset += this.AnswerLength();
    this.PreMissionQuestionsLength = offset;
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      Length: this.Length,
      Question: this.Question,
      Reserved: this.Reserved,
      Answer: this.Answer
    };
  }

  protected abstract QuestionLength();

  protected abstract AnswerLength();

  protected toHexString() {

    const hex = "";

    let offset = 0;
    writeShort(hex, this.Length, 0x0);
    if (this.Length === 0) {
      return;
    }
    writeChar(hex, this.Question, 0x2, this.QuestionLength());
    offset = 0x2;
    offset += this.QuestionLength();
    writeByte(hex, this.Reserved, offset);
    offset += 1;
    writeChar(hex, this.Answer, offset, this.AnswerLength());
    offset += this.AnswerLength();
    return hex;
  }

  public getLength(): number {
    return this.PreMissionQuestionsLength;
  }
}
