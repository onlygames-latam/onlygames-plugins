const childProcess = require("child_process");
const path = require("path");
const rootDir = path.resolve(__dirname, "../");

function runCommand(command, args = []) {
  return new Promise((resolve, reject) => {
    const child = childProcess.exec(command, { cwd: rootDir }, function (error, stdout, stderr) {
      if (error) {
        reject(error);
      } else {
        resolve();
      }
    });

    child.stdout.pipe(process.stdout);
    child.stderr.pipe(process.stderr);
  });
}

module.exports = runCommand;
