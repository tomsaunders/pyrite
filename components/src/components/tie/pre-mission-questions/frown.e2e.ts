import { newE2EPage } from "@stencil/core/testing";

describe("pyrite-tie-pre-mission-questions", () => {
  it("renders", async () => {
    const page = await newE2EPage();
    await page.setContent("<pyrite-tie-pre-mission-questions></pyrite-tie-pre-mission-questions>");

    const element = await page.find("pyrite-tie-pre-mission-questions");
    expect(element).toHaveClass("hydrated");
  });

  it('contains a "Profile Page" button', async () => {
    const page = await newE2EPage();
    await page.setContent("<pyrite-tie-pre-mission-questions></pyrite-tie-pre-mission-questions>");

    const element = await page.find("pyrite-tie-pre-mission-questions >>> button");
    expect(element.textContent).toEqual("Profile page");
  });
});
