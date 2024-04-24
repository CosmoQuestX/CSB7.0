/**
 * Moves the node modules folder to the correct path.
 * We use a node script instead of bash so that we're OS independent.
 * We *assume* package.json is at the root of the project.
 */

const path = require("path");
const fs = require("fs-extra");

const finder = require('find-package-json');
const ROOT = path.dirname(finder(module).next().filename);

const sourcePath = path.join(ROOT, "node_modules");
const targetPath = path.join(ROOT, "csb", "node_modules");

fs.moveSync(sourcePath, targetPath, { overwrite: true });