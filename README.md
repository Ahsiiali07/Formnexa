# Formnexa

**Formnexa** is a full-stack Laravel 12 web platform and mobile REST API (`/api/v1`) providing three complete document workflows in one workspace — usable immediately without login, with optional account registration for cross-device history sync:

1. **Invoice & 12 Business Document Generators**
   - Supports **Invoice**, **Receipt**, **Quote**, **Estimate**, **Credit Note**, **Purchase Order**, **Sales Order**, **Proforma Invoice**, **Timesheet**, **Work Order**, **Account Statement**, and **Packing Slip**.
   - 6 visual layout engines (`modern`, `classic`, `bold`, `minimal`, `stripe`, `compact`), 20+ industry templates, 24 global currencies, custom cover banners, logos, digital signature pad, payment links, A4 PDF export, and direct client email delivery with PDF attachment.
2. **Resume Builder & Smart CV Importer**
   - 10 layout engines (`flow_sidebar`, `editorial_figma`, `timeline_pro`, `luxe_split`, `executive_bold`, `creative_card`, `tech_stack`, `minimal_swiss`, `ats_classic`, `compact_onepage`) and 15+ designer presets.
   - Smart CV parser extracts structured personal details, summary, work experience, education, skills, languages, awards, and projects from uploaded **PDF**, **DOCX**, **TXT**, **MD**, **JSON**, or pasted text.
   - Live editor + crisp A4 vector PDF export and shareable public links.
3. **10-Tool PDF Converter Suite**
   - **Convert TO PDF (5 tools):** JPG/PNG/WebP to PDF, Word (DOCX/RTF/ODT/TXT) to PDF, PowerPoint (PPTX) to PDF, Excel/CSV/ODS to PDF, HTML (code/file/public URL) to PDF.
   - **Convert FROM PDF (5 tools):** PDF to JPG (browser PDF.js on web; server-side Node `pdfjs-dist` + `@napi-rs/canvas` for mobile/API), PDF to Word (DOCX editable & visual modes), PDF to PowerPoint (PPTX visual & editable modes), PDF to Excel (XLSX table extraction), PDF to Archival PDF/A-1b & PDF/A-3b.
4. **Mobile REST API (`/api/v1`)**
   - Full stateless JSON/binary REST API with optional Bearer token authentication (`api_tokens`) and guest document claiming (`guest_invoice_uuids`, `guest_resume_uuids`).
   - See [`docs/MOBILE_API.md`](docs/MOBILE_API.md) for complete endpoint specifications.

---

## Packages Used

### Backend (PHP / Composer — `composer.json`)

| Package | Version | Purpose |
|---|---|---|
| `laravel/framework` | `^12.0` | Core MVC framework, routing, validation, migrations, mail, and API middleware |
| `barryvdh/laravel-dompdf` | `^3.1` | Renders HTML/Blade templates into A4 PDFs for invoices, resumes, and TO-PDF conversions |
| `phpoffice/phpword` | `^1.4` | Reads RTF/ODT documents and generates editable/visual `.docx` files for PDF to Word |
| `phpoffice/phppresentation` | `^1.2` | Generates `.pptx` widescreen slide decks for PDF to PowerPoint |
| `phpoffice/phpspreadsheet` | `^4.5` | Reads `.xlsx`/`.xls`/`.csv`/`.ods` and builds `.xlsx` workbooks for PDF to Excel |
| `smalot/pdfparser` | `^2.13` | Extracts text & coordinate tables from PDFs for CV Import, PDF to Word/PPTX/Excel |
| `tecnickcom/tcpdf` | `^7.0` | Generates ISO 19005 archival **PDF/A-1b** and **PDF/A-3b** documents |
| `laravel/tinker` | `^2.10.1` | Interactive artisan REPL |
| `phpunit/phpunit` | `^11.5.3` | Automated feature and unit testing (`require-dev`) |
| `laravel/pint` | `^1.13` | PHP code style formatter (`require-dev`) |

### Frontend & Server-Side Rendering (Node.js / NPM — `package.json`)

| Package | Version | Purpose |
|---|---|---|
| `pdfjs-dist` | `^6.5.438` | Client-side PDF rendering in browser (`resources/js/pdf-tools.js`) **and** server-side PDF page rasterization (`scripts/pdf-render.mjs`) |
| `@napi-rs/canvas` | *(installed via `pdfjs-dist`)* | Native 2D canvas used by `scripts/pdf-render.mjs` on the server to render PDF pages into JPEG bytes |
| `alpinejs` | `^3.17.5` | Reactive UI state for the Invoice Studio, Resume Builder, and Converter Workspace |
| `jszip` | `^3.10.2` | Client-side multi-page JPG ZIP bundling in browser |
| `tailwindcss` + `@tailwindcss/vite` | `^4.0.0` | Utility-first styling pipeline |
| `vite` + `laravel-vite-plugin` | `^6.0.11` / `^1.2.0` | Frontend asset bundling (`resources/css/app.css`, `resources/js/app.js`) |
| `axios` | `^1.7.4` | HTTP client helper |

---

## Server & Live Deployment Requirements

### 1. PHP & Required Extensions
- **PHP `>= 8.2`**
- Required PHP extensions:
  - `gd` (image normalization, transparent PNG/WebP flattening, JPEG conversion)
  - `exif` (smartphone JPEG EXIF orientation auto-rotation)
  - `zip` (`ZipArchive` for DOCX/PPTX/XLSX parsing and multi-page ZIP downloads)
  - `dom`, `xml`, `xmlreader`, `xmlwriter`, `simplexml`, `libxml` (OOXML parsing and DomPDF)
  - `mbstring`, `fileinfo`, `intl`, `openssl`, `curl`, `bcmath`, `ctype`, `json`
  - `pdo_mysql` (or `pdo_sqlite`)
- **`proc_open` and `exec` must NOT be disabled** in `php.ini` (`disable_functions`), as `Symfony\Component\Process\Process` invokes Node (`scripts/pdf-render.mjs`) for server-side PDF rasterization.

### 2. Node.js Requirements (Build **and** Live Server Runtime)
> **Important:** Node.js is required **both at build time and at runtime** on the production server.

- **Node.js `>= 18`** (tested on **Node.js `v22.14.0`**) and **npm**.
- **At build time:** `npm run build` compiles Vite assets into `public/build/` (which is gitignored).
- **At runtime on the live server:**
  - `scripts/pdf-render.mjs` executes `node` using `pdfjs-dist` and `@napi-rs/canvas` whenever the API (or mobile app) converts PDFs via **`pdf-to-jpg`**, **`pdf-to-powerpoint` (`visual`)**, **`pdf-to-word` (`visual` / scanned PDFs)**, or **`pdf-to-pdfa`**.
  - **Do NOT delete `node_modules/` after `npm run build`**, and **always run `npm ci` (or `npm install`) directly on the target server OS** (do not copy `node_modules` from Windows to Linux, because `@napi-rs/canvas` installs OS-specific native binaries such as `@napi-rs/canvas-linux-x64-gnu` vs `win32-x64-msvc`).
  - If `node` is installed via `nvm` or outside the web server/PHP-FPM `PATH`, set `NODE_BINARY` in `.env`:
    ```env
    NODE_BINARY=/usr/bin/node
    # Windows example:
    # NODE_BINARY="C:\Program Files\nodejs\node.exe"
    ```

### 3. Recommended `php.ini` & Web Server Limits
File converters accept uploads up to **25 MB** per PDF/PPTX (and up to **30 images** for `jpg-to-pdf`):
```ini
upload_max_filesize = 32M
post_max_size = 64M
memory_limit = 256M
max_execution_time = 120
```
If using Nginx, also increase the upload limit in your server block:
```nginx
client_max_body_size 64M;
```

### 4. Storage, Fonts & Optional Chrome
- **Writable directories:** Ensure the web server user has write access to `storage/` (`storage/app`, `storage/framework`, `storage/logs`, `storage/fonts`), `bootstrap/cache/`, and the OS temp directory (`sys_get_temp_dir()`).
- **PDF/A Fonts (`storage/app/tcpdf-fonts`):** `DocumentConverter::pdfToPdfA()` checks `storage/app/tcpdf-fonts` (`K_PATH_FONTS`) for `dejavusans` and `pdfahelvetica` TCPDF compiled font files. Because `storage/app/.gitignore` excludes files inside `storage/app/`, make sure `storage/app/tcpdf-fonts/` is present on the live server if you use `pdf-to-pdfa`.
- **Headless Chrome (Optional):** `html-to-pdf` checks for Google Chrome on Windows (`C:\Program Files\Google\Chrome\Application\chrome.exe`) for Flexbox/Grid rendering and automatically falls back to DomPDF when absent.
- **Browser PDF.js Worker:** Keep `public/vendor/pdfjs/pdf.worker.min.mjs` deployed in `public/`.

---

## Production Deployment Steps

```bash
# 1. Clone repository
git clone https://github.com/Ahsiiali07/Formnexa.git
cd Formnexa

# 2. Install PHP dependencies (production)
composer install --no-dev --optimize-autoloader

# 3. Install Node dependencies ON the server OS & build Vite assets
#    (Keep node_modules/ in place for runtime scripts/pdf-render.mjs!)
npm ci
npm run build

# 4. Configure environment
cp .env.example .env
php artisan key:generate
```

Edit `.env` for your live server:
```env
APP_NAME=Formnexa
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Required for Invoice Email sending
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-user
MAIL_PASSWORD=your-smtp-password
MAIL_FROM_ADDRESS="billing@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Optional: explicit Node path if php-fpm/Apache cannot resolve `node` from PATH
# NODE_BINARY=/usr/bin/node
```

Finish setup on the server:
```bash
# 5. Run database migrations (creates users, invoices, invoice_items, resumes, api_tokens, sessions, cache, jobs)
php artisan migrate --force

# 6. Link public storage & optimize caches
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Point your Apache `DocumentRoot` or Nginx `root` to the **`/public`** directory.

---

## Local Development

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Run both Laravel and Vite concurrently (or individually):
```bash
composer run dev
```

---

## Running Tests

`phpunit.xml` runs against an isolated in-memory SQLite database so your local database is never modified:
```bash
php artisan test
```
