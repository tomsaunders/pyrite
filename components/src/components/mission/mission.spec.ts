import { PyriteMission } from "./mission";

describe("app-profile", () => {
  it("builds", () => {
    expect(new PyriteMission()).toBeTruthy();
  });

  describe("normalization", () => {
    it("returns a blank string if the name is undefined", () => {
      const component = new PyriteMission();
    });
  });
});
