#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
PUBLIC_DIR="$REPO_DIR/.local-preview/public"

if [[ ! -f "$PUBLIC_DIR/blog/wp-config.php" ]]; then
    echo "Run ./wordpress/scripts/setup-local.sh first." >&2
    exit 1
fi

echo "Portfolio: http://127.0.0.1:8080/"
echo "Blog:      http://127.0.0.1:8080/blog/"
php -S 127.0.0.1:8080 -t "$PUBLIC_DIR" "$SCRIPT_DIR/router.php"
