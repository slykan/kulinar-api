# Deploy: javni (server-rendered) dio kulinar.app + Flutter na /app

Server: cPanel korisnik `kulinar`. Laravel je u `~/api`, docroot je `~/public_html`
(ondje su `index.php` Laravela, `storage` link i Flutter build).

## Redoslijed (sve kao korisnik kulinar, NE kao root)

1. **Laravel** — u `~/api`:
   ```bash
   cd ~/api && git pull && php artisan migrate --force && php artisan optimize:clear && php artisan optimize
   ```
   `migrate` dodaje i uredničke recepte (korisnik „Kulinar.app", urednistvo@kulinar.app); recept s istim slugom se ne dira.
2. **Flutter web u /app** — raspakirati `kulinar.app/kulinar-web-app.zip` (sadržaj `build/web/`) (build s `--base-href /app/`)
   uploadati u `~/public_html/app/`.
3. **Očistiti stari Flutter iz korijena** `~/public_html/` — obrisati:
   `index.html`, `main.dart.js`, `flutter.js`, `flutter_bootstrap.js`, `version.json`,
   `manifest.json`, `assets/`, `canvaskit/`.
   **Ostaviti:** `index.php`, `storage`, `favicon.png`, `icons/`, `.htaccess` (zamijeniti u koraku 4).
4. Iz ovog foldera (`deploy/public_html/`) kopirati u `~/public_html/`:
   `.htaccess`, `ads.txt`, `robots.txt`, `privacy.html` (stara adresa politike privatnosti za Google Play),
   `flutter_service_worker.js` (gasi stari service worker kod starih posjetitelja).

## Provjera
- `https://kulinar.app/` — HTML naslovnica (ne Flutter)
- `https://kulinar.app/ads.txt` — tekst `google.com, pub-3054841074530422, ...`
- `https://kulinar.app/sitemap.xml` — XML
- `https://kulinar.app/nepostoji` — 404
- `https://kulinar.app/app/` — Flutter aplikacija; prijava Googleom vraća na `/app/#/google-callback`
- `https://kulinar.app/api/posts` — JSON (mobilne aplikacije)
