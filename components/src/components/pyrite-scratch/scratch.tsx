import { Component, Element, h, JSX, Prop, State } from "@stencil/core";
import { TFR } from "../../model/pilot/tfr";

import { Mission } from "../..//model/TIE";

@Component({
  tag: "pyrite-scratch",
  styleUrl: "scratch.scss"
})
export class Scratch {
  @State() public file: TFR;

  public componentWillLoad(): void {
    const file = "tf27good.tfr";
    fetch(`http://localhost:4444/pyrite/api/${file}`)
      .then((res: Response) => res.arrayBuffer())
      .then((value: ArrayBuffer) => {
        this.file = new TFR(value);
        console.log(this.file);
      });

    const path = "B2M1FW.TIE";
    fetch(`http://localhost:4444/pyrite/api/${path}`)
      .then((res: Response) => res.arrayBuffer())
      .then((fileBuffer: ArrayBuffer) => {
        console.time(path);
        const tie = new Mission(fileBuffer);
        console.log(`Loaded ${path} which has ${tie.FileHeader.NumFGs} FGs`);
        console.timeEnd(path);
      });
  }

  public render(): JSX.Element {
    return <pyrite-resources />;
  }
}
