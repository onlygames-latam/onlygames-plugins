const packageJSON = require("../package.json");
const fs = require("fs");
const path = require("path");
const runCommand = require("./run-command");

async function updatePackageVersion(versionCommand) {
  try {
    console.log("⏳ Preparing to increase package.json version...");
    await runCommand(`npm version ${versionCommand}`);
    console.log("✅ succesfuly updated packagev.json version\n");
  } catch (err) {
    console.error("❌ Failure while trying tu update the package.json version");
    throw new Error(err);
  }
}
async function updateComposerVersion(versionCommand) {
  try {
    console.log("⏳ Preparing to increase composer.json version...");
    await runCommand(`npx composer-version ${versionCommand}`);
    console.log("✅ succesfuly updated composer.json version\n");
  } catch (err) {
    console.error("❌ Failure while trying tu update the composer.json version");
    throw new Error(err);
  }
}

async function changeVersion() {
  try {
    const [source, filePath, versionCommand] = process.argv;

    if (!versionCommand) {
      throw new Error("Please specify a version to bump: mayor | minor | patch");
    }

    await updatePackageVersion(versionCommand);
    await updateComposerVersion(versionCommand);
  } catch (err) {
    throw new Error(err);
  }
}

changeVersion();
