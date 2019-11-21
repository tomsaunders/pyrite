import { PyriteResources } from "./pyrite-resources";

describe("PyriteResources", () => {
  it("builds", () => {
    expect(new PyriteResources()).toBeTruthy();
  });
});
