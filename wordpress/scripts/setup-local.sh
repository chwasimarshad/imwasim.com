#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
DOWNLOAD_DIR="/tmp/imwasim-wp-downloads"
PREVIEW_DIR="$REPO_DIR/.local-preview"
PUBLIC_DIR="$PREVIEW_DIR/public"
BLOG_DIR="$PUBLIC_DIR/blog"
WP_CLI="$DOWNLOAD_DIR/wp-cli.phar"
LOCAL_URL="http://127.0.0.1:8080/blog"
LOCAL_PASSWORD="LocalPreview!2026"

for package in wordpress.zip sqlite.zip rank-math.zip site-kit.zip redirection.zip spectra.zip wp-cli.phar; do
    if [[ ! -f "$DOWNLOAD_DIR/$package" ]]; then
        echo "Missing $DOWNLOAD_DIR/$package" >&2
        exit 1
    fi
done

rm -rf "$PREVIEW_DIR"
mkdir -p "$PUBLIC_DIR" "$BLOG_DIR"

for item in index.html 404.html CNAME favicon.ico favicon.svg site.webmanifest robots.txt sitemap.xml llms.txt css js img fonts books; do
    ln -s "$REPO_DIR/$item" "$PUBLIC_DIR/$item"
done

unzip -q "$DOWNLOAD_DIR/wordpress.zip" -d "$PREVIEW_DIR/core"
cp -R "$PREVIEW_DIR/core/wordpress/." "$BLOG_DIR/"
rm -rf "$PREVIEW_DIR/core"

unzip -q "$DOWNLOAD_DIR/sqlite.zip" -d "$BLOG_DIR/wp-content/plugins"
unzip -q "$DOWNLOAD_DIR/rank-math.zip" -d "$BLOG_DIR/wp-content/plugins"
unzip -q "$DOWNLOAD_DIR/site-kit.zip" -d "$BLOG_DIR/wp-content/plugins"
unzip -q "$DOWNLOAD_DIR/redirection.zip" -d "$BLOG_DIR/wp-content/plugins"
unzip -q "$DOWNLOAD_DIR/spectra.zip" -d "$BLOG_DIR/wp-content/plugins"

cp -R "$REPO_DIR/wordpress/theme/imwasim-blog" "$BLOG_DIR/wp-content/themes/"
mkdir -p "$BLOG_DIR/wp-content/themes/imwasim-blog/assets/fonts" "$BLOG_DIR/wp-content/database"
cp "$REPO_DIR/fonts/inter-latin.woff2" "$BLOG_DIR/wp-content/themes/imwasim-blog/assets/fonts/"
cp "$REPO_DIR/fonts/jetbrains-mono-latin.woff2" "$BLOG_DIR/wp-content/themes/imwasim-blog/assets/fonts/"

cp "$BLOG_DIR/wp-content/plugins/sqlite-database-integration/db.copy" "$BLOG_DIR/wp-content/db.php"

cat > "$BLOG_DIR/wp-config.php" <<'PHP'
<?php
define('DB_ENGINE', 'sqlite');
define('DB_DIR', __DIR__ . '/wp-content/database');
define('DB_FILE', 'imwasim-blog.sqlite');
define('DB_NAME', 'imwasim_blog');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_HOST', '');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('AUTH_KEY',         'local-preview-auth-key-change-in-production');
define('SECURE_AUTH_KEY',  'local-preview-secure-auth-key-change-in-production');
define('LOGGED_IN_KEY',    'local-preview-logged-in-key-change-in-production');
define('NONCE_KEY',        'local-preview-nonce-key-change-in-production');
define('AUTH_SALT',        'local-preview-auth-salt-change-in-production');
define('SECURE_AUTH_SALT', 'local-preview-secure-auth-salt-change-in-production');
define('LOGGED_IN_SALT',   'local-preview-logged-in-salt-change-in-production');
define('NONCE_SALT',       'local-preview-nonce-salt-change-in-production');
define('WP_HOME', 'http://127.0.0.1:8080/blog');
define('WP_SITEURL', 'http://127.0.0.1:8080/blog');
define('WP_ENVIRONMENT_TYPE', 'local');
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
$table_prefix = 'wp_';
if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-settings.php';
PHP

php "$WP_CLI" core install --path="$BLOG_DIR" --url="$LOCAL_URL" --title="Wasim Arshad | Architecture, AI & Engineering Leadership" --admin_user="wasim" --admin_password="$LOCAL_PASSWORD" --admin_email="chouhdarywasim@gmail.com" --skip-email
php "$WP_CLI" theme activate imwasim-blog --path="$BLOG_DIR"
php "$WP_CLI" plugin activate sqlite-database-integration seo-by-rank-math google-site-kit redirection ultimate-addons-for-gutenberg --path="$BLOG_DIR"
php "$WP_CLI" eval-file "$REPO_DIR/wordpress/scripts/seed-content.php" --path="$BLOG_DIR"

echo
echo "Local WordPress is ready: $LOCAL_URL"
echo "Admin: $LOCAL_URL/wp-admin/"
echo "Username: wasim"
echo "Preview-only password: $LOCAL_PASSWORD"
