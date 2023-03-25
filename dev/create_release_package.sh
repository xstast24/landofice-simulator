#!/usr/bin/env bash

PACKAGE_NAME="simulator-release"
if [ -n "$1" ]; then  # if any arg given, show help
  echo "Creates a release package '$PACKAGE_NAME' (dir & zip) that contains everything needed to run the simulator on the server."
  echo "Zip package is also created for easy upload to LoI forum etc. Package is created only locally, not uploaded anywhere."
  echo "Example usage (no params needed): ${FUNCNAME[0]}" && return
fi

RELEASE_FILES=(
  images
  src
  ajax.js
  ajax-loader.gif
  api.php
  config.ini
  config.js
  constants.php
  favicon.ico
  index.php
  jednotky.php
  jednotky.xml
  pleneni.php
  simulator_engine.php
  sorttable.js
  wz_tooltip.js
)

SCRIPT_DIR=$(dirname "${0}")
SIMULATOR_ROOT=$(dirname "${SCRIPT_DIR}")
RELEASE_DIR="$SIMULATOR_ROOT/$PACKAGE_NAME"

echo "Creating release dir"
rm -rf "$RELEASE_DIR"
mkdir -p "$RELEASE_DIR"
# copy contents
for path in "${RELEASE_FILES[@]}"; do
  cp -a "$SIMULATOR_ROOT/$path" "$RELEASE_DIR"  # -a to recursively copy (also dirs) and preserve file attributes
done

echo "Creating release zip"
zip -q -r "$SIMULATOR_ROOT/$PACKAGE_NAME".zip "$RELEASE_DIR"  # -q to not show all processed files
