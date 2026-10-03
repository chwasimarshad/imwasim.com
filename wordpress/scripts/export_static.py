#!/usr/bin/env python3
"""Export the local WordPress blog as clean GitHub Pages HTML."""

from __future__ import annotations

import re
import shutil
import sys
from pathlib import Path
from urllib.error import URLError
from urllib.request import urlopen

REPO = Path(__file__).resolve().parents[2]
THEME = REPO / "wordpress" / "theme" / "imwasim-blog"
OUTPUT = REPO / "blog"
LOCAL_BASE = "http://127.0.0.1:8080/blog/"
PRODUCTION_BASE = "https://imwasim.com/blog/"
ARTICLE_SLUG = "model-context-protocol-mcp-introduction"

PAGES = {
    "": OUTPUT / "index.html",
    f"{ARTICLE_SLUG}/": OUTPUT / ARTICLE_SLUG / "index.html",
}

GOOGLE_TAG = """<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-L5KCTN0P42"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-L5KCTN0P42');
</script>
"""


def fetch(path: str) -> str:
    url = LOCAL_BASE + path
    try:
        with urlopen(url, timeout=15) as response:
            return response.read().decode("utf-8")
    except URLError as error:
        raise SystemExit(
            f"Could not read {url}. Start the preview with "
            "./wordpress/scripts/start-local.sh first.\n"
            f"Reason: {error}"
        ) from error


def clean(html: str) -> str:
    style_ids = (
        "wp-img-auto-sizes-contain-inline-css|wp-emoji-styles-inline-css|"
        "wp-block-library-inline-css|classic-theme-styles-inline-css|global-styles-inline-css"
    )
    html = re.sub(
        rf'<style id="(?:{style_ids})">.*?</style>\s*', "", html, flags=re.DOTALL
    )
    html = re.sub(
        r'<script type="speculationrules">.*?</script>\s*', "", html, flags=re.DOTALL
    )
    html = re.sub(
        r'<script id="wp-emoji-settings".*?</script>\s*<script type="module">.*?</script>\s*',
        "",
        html,
        flags=re.DOTALL,
    )
    html = re.sub(r'<meta name="generator"[^>]*>\s*', "", html)
    html = re.sub(r"<link rel=['\"]shortlink['\"][^>]*>\s*", "", html)

    filtered_lines = []
    for line in html.splitlines():
        if any(
            marker in line
            for marker in (
                "dns-prefetch",
                "application/rss+xml",
                "application/json+oembed",
                "text/xml+oembed",
                'rel="https://api.w.org/"',
                'rel="EditURI"',
            )
        ):
            continue
        filtered_lines.append(line)
    html = "\n".join(filtered_lines) + "\n"

    html = re.sub(
        r"<link rel='stylesheet' id='imwasim-blog-css'[^>]*>",
        '<link rel="stylesheet" href="/blog/assets/style.css">',
        html,
    )
    html = re.sub(
        r'<script id="imwasim-blog-js"[^>]*></script>',
        '<script src="/blog/assets/theme.js" defer></script>',
        html,
    )

    html = html.replace(LOCAL_BASE, "/blog/")
    html = html.replace("http%3A%2F%2F127.0.0.1%3A8080%2Fblog%2F", "")

    html = re.sub(
        r'(<meta property="og:url" content=")/blog/', rf"\g<1>{PRODUCTION_BASE}", html
    )
    html = re.sub(
        r'(<link rel="canonical" href=")/blog/', rf"\g<1>{PRODUCTION_BASE}", html
    )
    html = html.replace('"mainEntityOfPage":"/blog/', f'"mainEntityOfPage":"{PRODUCTION_BASE}')
    html = html.replace('"url":"/blog/"', f'"url":"{PRODUCTION_BASE}"')

    if "googletagmanager.com/gtag/js?id=G-L5KCTN0P42" not in html:
        html = html.replace("</head>", GOOGLE_TAG + "</head>")
    html = html.replace("<!doctype html>", "<!doctype html>\n<!-- Static export generated from the local WordPress authoring site. -->", 1)

    forbidden = ("127.0.0.1", "/wp-json/", "xmlrpc.php", "/wp-content/", "/wp-includes/")
    leftovers = [value for value in forbidden if value in html]
    if leftovers:
        raise RuntimeError(f"WordPress runtime references remain: {', '.join(leftovers)}")
    return html


def copy_assets() -> None:
    assets = OUTPUT / "assets"
    if assets.exists():
        shutil.rmtree(assets)
    (assets / "fonts").mkdir(parents=True)

    css = (THEME / "style.css").read_text(encoding="utf-8")
    css = css.replace("url(assets/fonts/", "url(fonts/")
    (assets / "style.css").write_text(css, encoding="utf-8")
    shutil.copy2(THEME / "assets" / "js" / "theme.js", assets / "theme.js")
    shutil.copy2(REPO / "fonts" / "inter-latin.woff2", assets / "fonts" / "inter-latin.woff2")
    shutil.copy2(
        REPO / "fonts" / "jetbrains-mono-latin.woff2",
        assets / "fonts" / "jetbrains-mono-latin.woff2",
    )


def write_support_files() -> None:
    (OUTPUT / "llms.txt").write_text(
        """# Wasim Arshad — Architecture, AI & Engineering Leadership

> Practical writing about AI agents, software architecture, intelligent automation, and engineering leadership.

## Articles

- [Model Context Protocol (MCP): The Missing Link for AI Agents](https://imwasim.com/blog/model-context-protocol-mcp-introduction/): A story-driven guide to MCP, AI agents, tool calling, context engineering, Anthropic, OpenAI, and secure AI automation.
""",
        encoding="utf-8",
    )


def validate_internal_links() -> None:
    checked: set[str] = set()
    for html_file in PAGES.values():
        html = html_file.read_text(encoding="utf-8")
        for reference in re.findall(r'(?:href|src)="([^"]+)"', html):
            if not reference.startswith("/blog/"):
                continue
            route = reference.split("#", 1)[0].split("?", 1)[0]
            if route in checked:
                continue
            checked.add(route)
            relative = route.removeprefix("/blog/")
            target = OUTPUT / relative
            if route.endswith("/"):
                target /= "index.html"
            if not target.is_file():
                raise RuntimeError(f"Broken static reference: {reference} -> {target}")
    print(f"Validated {len(checked)} internal blog routes and assets.")


def main() -> int:
    copy_assets()
    for path, destination in PAGES.items():
        destination.parent.mkdir(parents=True, exist_ok=True)
        destination.write_text(clean(fetch(path)), encoding="utf-8")
        print(f"Exported /blog/{path} -> {destination.relative_to(REPO)}")
    write_support_files()
    validate_internal_links()
    print("Static blog export complete.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
