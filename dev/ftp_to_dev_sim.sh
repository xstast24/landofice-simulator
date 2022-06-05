#!/usr/bin/env bash

if [[ "$1" == "--help" || "$1" == "-h" || -z "$1" || -n "$3" ]]; then
        echo "Upload files to dev simulator webserver using FTP."
        echo "Param \$1 - TARGET = what to upload to the server, e.g. file or dir"
        echo "    Note: './src' uploads the whole src dir (result is /destination/src), whereas '/src/*' uploads contents of src dir (result is /destination/*"
        echo "Param \$2 - DESTINATION (optional) = where to upload on the server, defaults to '/' for the web root."
        echo "    Note: Relative to default FTP destination that is opened on FTP connection, usually the dir with web content (configurable in web administration -> FTP)"
        SCRIPT_NAME=$(basename "${0}")
        echo "USAGE examples (the following two examples are equivalents):"
        echo "    ${SCRIPT_NAME} ./images/* /images"
        echo "    ${SCRIPT_NAME} ./images /"
        echo "1 $1"
        echo "2 $2"
        exit 42
fi

USER="landofnostalgia"
PWD="sdileneHeslo123"
HOST="konik.endora.cz" #FTP server

TARGET="$1"      #what will be uploaded to the server
DESTINATION="/"
[[ -n "$2" ]] && DESTINATION="$2"


PLATFORM=$(uname -s)
if [[ "Darwin" == *"$PLATFORM"* ]]; then
  echo "Detected Mac platform, trying to upload via ncftp..."
  which ncftp 1>/dev/null || {
    echo "ncftp not installed. Install via Homebrew: brew install ncftp"
    exit 1
  }
  ncftpput -R -u "$USER" -p "$PWD" "$HOST" "$DESTINATION" $TARGET || { #TARGET is intentionally not quoted to expand any wildcards like /images/*
    echo "ERROR: Upload failed!"
    exit 1
  }
elif [[ "Linux" == *"$PLATFORM"* ]]; then
  echo "Detected Linux platform, trying to upload via ncftp..."
  which ncftp || {
    echo "ncftp not installed. Install via 'yum install ncftp' or 'apt-get install ncftp'"
    exit 1
  }
  ncftpput -R -u landofnostalgia -p sdileneHeslo123 konik.endora.cz / ./poznamky/* || {
    echo "ERROR: Upload failed!"
    exit 1
  }
elif [[ "MINGW" == *"$PLATFORM"* || "CYGWIN" == *"$PLATFORM"* ]]; then
  echo "Detected Windows platform - NOT IMPLEMENTED. PS: You can implement, it's easy (I just don't have a way to try it)."
  echo "Just check out mput or install ncftp in git bash (SDK version needed) and then it is same as for Mac/Linux"
  exit 1
else
  echo "ERROR: Unknown platform: \"$PLATFORM\""
  exit 1
fi

echo "Upload finished"
