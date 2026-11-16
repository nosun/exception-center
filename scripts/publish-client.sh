#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TARGETS=(tradeExpress tradeManager console-new)

for project in "${TARGETS[@]}"; do
  target="${ROOT_DIR}/../${project}/packages/laravel-exception-center-client"
  mkdir -p "$target"
  rsync -a --delete \
    --exclude='vendor/' \
    --exclude='.git/' \
    --exclude='tests/' \
    "$ROOT_DIR/config.php" "$ROOT_DIR/package-README.md" "$ROOT_DIR/src" "$target/"
  cp "$ROOT_DIR/package-composer.json" "$target/composer.json"
  cp "$ROOT_DIR/package-README.md" "$target/README.md"
  echo "Published client to ${target}"
done
