import { MessageBase } from "./gen/message-base";
import { Constants } from "./constants";

export class Message extends MessageBase {
  public get MessageColourLabel(): string {
    return Constants.MESSAGECOLOR[this.MessageColour];
  }
  public MessageColour = 0;

  public toJSON(): object {
    const start = this.MessageColour === 0 ? 0 : 1;
    return {
      Message: this.Message.substr(start),
      MessageColour: this.MessageColourLabel,
      Triggers: this.Triggers,
      EditorNote: this.EditorNote,
      Trigger1OrTrigger2: this.Trigger1OrTrigger2
    };
  }

  protected afterConstruct() {
    const num = parseInt(this.Message[0], 10);
    if (!isNaN(num)) {
      this.MessageColour = num;
    }
  }
}
