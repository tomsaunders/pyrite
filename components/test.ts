import { readdirSync, readFileSync } from "fs";
import { Mission } from "./src/model/TIE";

const oldMissions = readdirSync("../tie94/MISSION/");

const missions = oldMissions.filter((mission) => mission.endsWith("TIE"));
console.time("Processing missions");
for (const path of missions) {
  const fileBuffer = readFileSync("../tie94/MISSION/" + path);
  const arrayBuffer = fileBuffer.buffer.slice(fileBuffer.byteOffset, fileBuffer.byteOffset + fileBuffer.byteLength);
  const tie = new Mission(arrayBuffer);
  console.log(`Loaded ${path} which has ${tie.FileHeader.NumFGs} FGs`);
  for (const fg of tie.FlightGroups) {
    if (fg.PermaDeathEnabled) {
      console.log("Perma Death: ", fg.toString(), " - Permanent ID: ", fg.PermaDeathID);
    }
  }
}
console.timeEnd("Processing missions");
