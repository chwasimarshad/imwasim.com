# Static blog export

The files in this directory are the GitHub Pages build of the locally authored WordPress blog. They contain no PHP, database access, WordPress core, admin panel, or plugin runtime.

To update the published pages:

1. Start the local WordPress preview.
2. Edit or add the article in WordPress.
3. Update the page map in `wordpress/scripts/export_static.py` when adding a new route.
4. Run `./wordpress/scripts/export-static.sh`.
5. Review and commit the generated HTML and assets.

GitHub Pages serves the listing at `/blog/` and the current article at `/blog/model-context-protocol-mcp-introduction/`.
