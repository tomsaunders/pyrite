import { Config } from "@stencil/core";
import { sass } from "@stencil/sass";

// https://stenciljs.com/docs/config

export const config: Config = {
  namespace: "pyrite",
  outputTargets: [
    {
      type: "www",
      serviceWorker: null
    },
    { type: "dist" }
  ],
  globalScript: "src/global/app.ts",
  globalStyle: "src/global/app.css",
  plugins: [sass()]
};
