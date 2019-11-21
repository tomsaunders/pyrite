import { newE2EPage } from "@stencil/core/testing";

describe("pyrite-resources", () => {
  it("renders", async () => {
    const page = await newE2EPage();
    await page.setContent("<pyrite-resources></pyrite-resources>");

    const element = await page.find("pyrite-resources");
    expect(element).toHaveClass("hydrated");
  });

  it('contains a "Profile Page" button', async () => {
    const page = await newE2EPage();
    await page.setContent("<pyrite-resources></pyrite-resources>");

    const element = await page.find("pyrite-resources ion-content ion-button");
    expect(element.textContent).toEqual("Profile page");
  });
});
