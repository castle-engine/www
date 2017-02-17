#!/bin/bash
set -eu

# This script expects to be run within www/scripts/ directory
# (it accesses htdocs/ and pack/ directories by relative paths).

TARGET_FILE=../htdocs/generated_versions.php
TMP_TARGET_FILE="$TARGET_FILE".new

SHELL_TARGET_FILE=../pack/generated_versions.sh
TMP_SHELL_TARGET_FILE="$SHELL_TARGET_FILE".new

# include, to know old versions
. "$SHELL_TARGET_FILE"

# This adds a line for explicitly given PROGRAM_NAME ($1)
# and version PROGRAM_VERSION ($2).
# You can use this in special cases if a program's binary
# can't accept --version parameter and you have to call this.
version_explicit ()
{
  PROGRAM_NAME="$1"
  PROGRAM_VERSION="$2"
  shift 2

  PROGRAM_NAME=`stringoper UpperCase $PROGRAM_NAME`

  echo "  define('VERSION_$PROGRAM_NAME', '$PROGRAM_VERSION');" >> "$TMP_TARGET_FILE"

  echo "GENERATED_VERSION_$PROGRAM_NAME=$PROGRAM_VERSION" >> "$TMP_SHELL_TARGET_FILE"
}

# Call program's binary ($1) to determine program version.
# Program name is also derived from binary name.
version_call ()
{
  PROGRAM_BINARY="$1"
  shift 1

  if which "$PROGRAM_BINARY" > /dev/null; then
    PROGRAM_NAME=`stringoper ExtractFileName $PROGRAM_BINARY`
    PROGRAM_VERSION=`$PROGRAM_BINARY --version`
    version_explicit "$PROGRAM_NAME" "$PROGRAM_VERSION"
  else
    OLD_VERSION_VAR_NAME="GENERATED_VERSION_${PROGRAM_NAME}"
    # Dynamic variable name in bash, see http://stackoverflow.com/questions/16553089/bash-dynamic-variable-names
    OLD_VERSION=${!OLD_VERSION_VAR_NAME}
    echo "Warning: Cannot execute ${PROGRAM_BINARY}. Will keep old version ${OLD_VERSION}"
    version_explicit "$PROGRAM_NAME" "$OLD_VERSION"
  fi
}

echo '<?php /* Version numbers automatically generated by generate_versions.sh */' > "$TMP_TARGET_FILE"

echo '# Version numbers automatically generated by generate_versions.sh' > "$TMP_SHELL_TARGET_FILE"

version_call castle
version_call lets_take_a_walk
version_call malfunction
version_call kambi_lines
version_call view3dscene
version_call rayhunter
version_call glViewImage
version_call glplotter
version_call glinformation
version_call gen_function
version_call mountains_of_fire
# glinformation_glut doesn't accept --version, but it should be considered
# to have the same version as glinformation.
version_explicit 'glinformation_glut' `glinformation --version`
version_explicit 'castle_game_engine' `castle-engine --version`
version_explicit demo_models 3.7.0

echo '?>' >> "$TMP_TARGET_FILE"

echo "Diff old vs new version:"
echo '--------------------'
set +e
diff -u "$TARGET_FILE" "$TMP_TARGET_FILE"
set -e
echo '--------------------'

mv -f "$TMP_TARGET_FILE" "$TARGET_FILE"
echo "$TARGET_FILE generated successfully"

mv -f "$TMP_SHELL_TARGET_FILE" "$SHELL_TARGET_FILE"
echo "$SHELL_TARGET_FILE generated successfully"
