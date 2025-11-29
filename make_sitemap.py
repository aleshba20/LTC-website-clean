import os, datetime, urllib.parse, html

BASE = "https://aleshba20.github.io/LTC-website-clean/"

def encode_url(u: str) -> str:
    """Percent-encode path, then XML-escape for <loc>."""
    parts = urllib.parse.urlsplit(u)
  
    path = urllib.parse.quote(parts.path, safe="/-._~")
    rebuilt = urllib.parse.urlunsplit((parts.scheme, parts.netloc, path, "", ""))
    return html.escape(rebuilt, quote=False)

urls = []
for root, _, files in os.walk("."):
    for f in files:
        if f.lower().endswith(".html"):
            rel = os.path.relpath(os.path.join(root, f), ".").replace("\\", "/")
            if rel.startswith("."):
                continue
            full = urllib.parse.urljoin(BASE, rel)
            urls.append(full)

today = datetime.date.today().isoformat()

lines = ['<?xml version="1.0" encoding="UTF-8"?>',
         '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">']
for u in sorted(urls, key=lambda s: s.lower()):
    safe = encode_url(u)
    lines.append(f'  <url><loc>{safe}</loc><lastmod>{today}</lastmod><changefreq>weekly</changefreq><priority>0.6</priority></url>')
lines.append('</urlset>')

with open("sitemap.xml", "w", encoding="utf-8", newline="\n") as f:
    f.write("\n".join(lines) + "\n")
