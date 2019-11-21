import { TagBase } from "./gen/tag-base";

export class Tag extends TagBase {
  public toString() {
    return this.Text;
  }
  protected afterConstruct() {
    this.TagLength = this.Length + 2;
  }
}
