# imwasim.com WordPress blog

This directory contains the version-controlled WordPress layer for `imwasim.com/blog`:

- `theme/imwasim-blog` is the custom blog theme.
- `scripts/setup-local.sh` builds an ignored local WordPress installation.
- `scripts/start-local.sh` serves the existing static portfolio and WordPress blog from one local origin.
- `scripts/seed-content.php` creates the initial MCP article and blog settings.

WordPress core, plugins, uploads, and the SQLite database are runtime artifacts and are not committed.

## Local preview

The setup script expects the official packages already downloaded to `/tmp/imwasim-wp-downloads`. It installs WordPress with SQLite, activates the theme and free plugins, and seeds the first article.

```bash
./wordpress/scripts/setup-local.sh
./wordpress/scripts/start-local.sh
```

Open `http://127.0.0.1:8080/blog/`. The local administrator is `wasim`; its preview-only password is printed by the setup script.

## Production requirements

GitHub Pages serves static files and cannot execute WordPress. Publishing `/blog` requires PHP hosting with MySQL/MariaDB and routing `imwasim.com/blog` to that WordPress installation. The theme works with standard WordPress hosting; the SQLite integration is for local preview only.

Install and activate these free plugins in production:

- Rank Math SEO — metadata, schema, XML sitemaps, and on-page checks.
- Site Kit by Google — Search Console and Analytics integration.
- Redirection — redirect management and 404 monitoring.
- Spectra — optional Gutenberg layout blocks.

Plugins support technical and on-page SEO. Off-page SEO still requires editorial distribution, earned links, references, and relationship building.
