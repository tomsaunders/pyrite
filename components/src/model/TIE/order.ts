import { OrderBase } from "./gen/order-base";
import { Constants } from "./constants";

export class Order extends OrderBase {
  public diffLimit = true;

  public get isSet(): boolean {
    return !!this.Order;
  }

  public toString() {
    const msg = [this.OrderLabel];
    if (this.Target1) {
      const t1 = this.lookup(this.Target1Type, this.Target1);
      msg.push(`${this.Target1TypeLabel} ${this.Target1} ${t1}`);
      if (this.Target2) {
        if (this.Target1OrTarget2) {
          msg.push("OR");
        }
        const t2 = this.lookup(this.Target2Type, this.Target2);
        msg.push(`${this.Target2TypeLabel} ${this.Target2} ${t2}`);
      }
    }
    if (this.Target3) {
      msg.push("THEN");
      const t3 = this.lookup(this.Target3Type, this.Target3);
      msg.push(`${this.Target3TypeLabel} ${this.Target3} ${t3}`);
      if (this.Target2) {
        if (this.Target3OrTarget4) {
          msg.push("OR");
        }
        const t4 = this.lookup(this.Target4Type, this.Target4);
        msg.push(`${this.Target4TypeLabel} ${this.Target4} ${t4}`);
      }
    }

    return msg.join(" ");
  }

  private lookup(type: number, instance: number): string {
    switch (type) {
      case 0:
        return "None";
      case 1:
        return this.TIE.getFlightGroup(instance).toString();
      case 2:
        return Constants.CRAFTTYPE[instance];
      case 3:
        return Constants.CRAFTCATEGORY[instance];
      case 4:
        return Constants.OBJECTCATEGORY[instance];
      case 5:
        return this.TIE.getIFF(instance);
      case 6:
        return Constants.ORDER[instance];
      case 7:
        return Constants.CRAFTWHEN[instance];
      case 8:
        const fgs = this.TIE.getGlobalGroup(instance);
        return fgs.map((fg) => fg.toString()).join(", ");
      case 9:
        return Constants.MISC[instance];
    }
    return "Unknown";
  }
}
