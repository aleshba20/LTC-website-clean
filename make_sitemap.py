import os, datetime, urllib.parse

BASE = "https://aleshba20.github.io/LTC-website-clean/"  # <- set your GH Pages base URL

urls = []
for root, _, files in os.walk("."):
    for f in files:
        if f.lower().endswith(".html"):
            rel = os.path.relpath(os.path.join(root, f), ".").replace("\\", "/")
            # Skip hidden/temporary files
            if rel.startswith("."): 
                continue
            urls.append(urllib.parse.urljoin(BASE, rel))

today = datetime.date.today().isoformat()

xml = ['<?xml version="1.0" encoding="UTF-8"?>',
       '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">']
for u in sorted(urls):
    xml.append(f"  <url><loc>{u}</loc><lastmod>{today}</lastmod><changefreq>weekly</changefreq><priority>0.6</priority></url>")
xml.append("</urlset>")

with open("sitemap.xml", "w", encoding="utf-8") as f:
    f.write("\n".join(xml))

print(f"Generated sitemap.xml with {len(urls)} URLs")
