import { Constants } from "../constants";

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

export abstract class PostMissionQuestionsBase extends PyriteBase implements Byteable {

  public PostMissionQuestionsLength = 0;

  public Length: number;
  public QuestionCondition: number;
  public QuestionType: number;
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
    this.QuestionCondition = getByte(hex, 0x2);
    this.QuestionType = getByte(hex, 0x3);
    this.Question = getChar(hex, 0x4, this.QuestionLength());
    offset = 0x4;
    offset += this.QuestionLength();
    this.Reserved = getByte(hex, offset);
    offset += 1;
    this.Answer = getChar(hex, offset, this.AnswerLength());
    offset += this.AnswerLength();
    this.PostMissionQuestionsLength = offset;
    this.afterConstruct();
  }

  public toJSON(): object {
    return {
      Length: this.Length,
      QuestionCondition: this.QuestionConditionLabel,
      QuestionType: this.QuestionTypeLabel,
      Question: this.Question,
      Reserved: this.Reserved,
      Answer: this.Answer
    };
  }

  public get QuestionConditionLabel() {
    return Constants.QUESTIONCONDITION[this.QuestionCondition] || "Unknown";
  }

  public get QuestionTypeLabel() {
    return Constants.QUESTIONTYPE[this.QuestionType] || "Unknown";
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
    writeByte(hex, this.QuestionCondition, 0x2);
    writeByte(hex, this.QuestionType, 0x3);
    writeChar(hex, this.Question, 0x4, this.QuestionLength());
    offset = 0x4;
    offset += this.QuestionLength();
    writeByte(hex, this.Reserved, offset);
    offset += 1;
    writeChar(hex, this.Answer, offset, this.AnswerLength());
    offset += this.AnswerLength();
    return hex;
  }

  public getLength(): number {
    return this.PostMissionQuestionsLength;
  }
}
