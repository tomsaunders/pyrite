import { TIEStringBase } from "./gen/tie-string-base";

export class TIEString extends TIEStringBase {
  public toString() {
    return this.Text;
  }
  protected afterConstruct() {
    this.TIEStringLength = this.Length + 2;
  }
}
