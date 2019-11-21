import { PreMissionQuestionsBase } from "./gen/pre-mission-questions-base";

export enum QuestionType {
  Officer = "Officer",
  Secret = "Secret"
}

export class PreMissionQuestions extends PreMissionQuestionsBase {
  public Type: QuestionType;

  protected afterConstruct() {
    if (this.Length === 0) {
      this.PreMissionQuestionsLength = 2;
    }
  }

  protected QuestionLength(): number {
    if (this.Length === 0) {
      return 0;
    }
    let text: string = String.fromCharCode.apply(null, new Uint8Array(this.hex.slice(2)));
    text = text.substr(0, this.Length);
    const splitter = String.fromCharCode(10);
    if (text.includes(splitter)) {
      const idx = text.indexOf(splitter);
      return idx;
    }
  }

  protected AnswerLength(): number {
    if (this.Length === 0) {
      return 0;
    }
    let text: string = String.fromCharCode.apply(null, new Uint8Array(this.hex.slice(2)));
    text = text.substr(0, this.Length);
    const splitter = String.fromCharCode(10);
    if (text.includes(splitter)) {
      const idx = text.indexOf(splitter);
      return this.Length - idx - 1;
    }
  }
}
