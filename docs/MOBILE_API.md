# Formnexa Mobile REST API (`/api/v1`)

Stateless JSON & binary REST API mirroring all Formnexa website flows:
1. **Authentication & Guest Document Sync** (Optional login, just like the website)
2. **Invoice & 12 Business Document Generators** (Create, Edit, Preview/Load, PDF Export, Email, Status, Delete, Templates & Config)
3. **Resume Builder & Smart CV Importer** (Import PDF/DOCX/TXT/JSON or raw text, Create, Edit, Load, PDF Export, Delete, Templates & Config)
4. **10-Tool PDF Converter Suite** (Convert TO PDF & Convert FROM PDF, including server-side PDF-to-JPG rendering)
5. **Unified History & Dashboard Stats**

---

## Common Conventions

- **Base Prefix:** `/api/v1`
- **Headers:**
  - `Accept: application/json` (always recommended; for binary conversion/PDF responses check `Content-Type`)
  - `Authorization: Bearer <token>` (required on protected endpoints, optional on guest-capable endpoints)
- **Error Responses:**
  - `401`: `{ "success": false, "message": "..." }`
  - `403`: `{ "success": false, "message": "..." }`
  - `404`: `{ "message": "..." }` or `{ "success": false, "message": "..." }`
  - `422`: Validation or conversion error (`{ "message": "...", "errors": { ... } }` or `{ "success": false, "message": "..." }`)

---

## 1. App Bootstrap & Catalogs

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/bootstrap` | None | Unified startup payload: 12 invoice generators, invoice styles/colors/currencies/terms/cover presets, resume styles/colors/fonts/densities, featured templates, and all 10 converter schemas. |
| `GET` | `/api/v1/invoices/config` | None | Invoice generators, 6 layout styles, 8 color swatches, 8 categories, 24 currencies, payment terms, cover presets. |
| `GET` | `/api/v1/invoices/templates` | None | Filterable invoice templates (`?category=`, `?style=`, `?popular=1`, `?q=`). |
| `GET` | `/api/v1/resumes/config` | None | 10 resume styles, 8 colors, 7 categories, fonts, densities, `default_content`, `blank_content`. |
| `GET` | `/api/v1/resumes/templates` | None | Filterable resume templates (`?category=`, `?style=`, `?popular=1`, `?q=`). |
| `GET` | `/api/v1/converters` | None | Grouped (`to_pdf`, `from_pdf`) and flat list of all 10 converter tools with field/option schemas. |
| `GET` | `/api/v1/converters/{tool}` | None | Single converter tool metadata & option schema. |

---

## 2. Authentication & Guest History Sync

Just like the website, **login is optional** for creating invoices, resumes, and converting files. Store guest-created `uuid`s locally on the device and pass them on `register`, `login`, or `claim` to permanently attach them to the user's account.

| Method | Endpoint | Auth | Body / Notes |
|---|---|---|---|
| `POST` | `/api/v1/auth/register` | None | `{ "name", "email", "password", "password_confirmation", "device_name?", "guest_invoice_uuids?": [], "guest_resume_uuids?": [] }` → returns `201` with `token`, `token_type: "Bearer"`, `user`, `claimed`. |
| `POST` | `/api/v1/auth/login` | None | `{ "email", "password", "device_name?", "guest_invoice_uuids?": [], "guest_resume_uuids?": [] }` → returns `200` with `token`, `user`, `claimed`. |
| `GET` | `/api/v1/auth/me` | Bearer | Returns authenticated `user` and `stats` (`total_count`, `paid_count`, `pending_count`, `total_billed`, `resume_count`). |
| `POST` | `/api/v1/auth/claim` | Bearer | `{ "guest_invoice_uuids?": [], "guest_resume_uuids?": [] }` → attaches unassigned guest documents to the signed-in user. |
| `POST` | `/api/v1/auth/logout` | Bearer | Revokes current token (pass `{ "all_devices": true }` to revoke all tokens). |

---

## 3. Unified History Dashboard

| Method | Endpoint | Auth | Query Params / Notes |
|---|---|---|---|
| `GET` | `/api/v1/history` | Bearer | Mirrors `/history` dashboard: returns `stats`, paginated `invoices` (`?q=`, `?status=all\|draft\|sent\|paid\|overdue`, `?per_page=`), and `resumes`. |

---

## 4. Invoice & 12 Business Documents Flow

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/invoices` | Optional | Authenticated user's paginated invoices (supports `?q=`, `?status=`, `?per_page=`), or guest invoices via `?uuids=uuid1,uuid2` (or `?uuids[]=...`). Includes `stats`. |
| `GET` | `/api/v1/invoices/history` | Optional | Quick recent list (up to 20 items) matching web drawer. Accepts `?uuids=` for guests. |
| `POST` | `/api/v1/invoices` | Optional | Create or update (if `uuid` is passed). Supports JSON or `multipart/form-data` (`items` as array or JSON string; `logo_data`/`cover_image`/`signature_data` strings OR `logo_file`/`cover_file`/`signature_file` image uploads). |
| `GET` | `/api/v1/invoices/{uuid}` | Optional | Load full invoice + `items` (returns `403` if owned by another user). |
| `PUT` / `PATCH` | `/api/v1/invoices/{uuid}` | Optional | Update invoice by `uuid` (same payload as `POST`). |
| `PATCH` | `/api/v1/invoices/{uuid}/status` | Bearer | `{ "status": "draft\|sent\|paid\|overdue" }`. |
| `DELETE` | `/api/v1/invoices/{uuid}` | Bearer | Deletes invoice & items owned by authenticated user. |
| `GET` | `/api/v1/invoices/{uuid}/pdf` | None | Downloads crisp A4 PDF (`application/pdf`). Add `?stream=1` for inline preview. |
| `POST` | `/api/v1/invoices/{uuid}/email` | Optional | `{ "to_email", "subject?", "message?" }` — emails attached PDF & promotes `draft` → `sent`. |
| `GET` | `/api/v1/public/invoices/{uuid}` | None | Public shareable client invoice JSON (mirrors `/invoice/{uuid}`). |

---

## 5. Resume Builder & Smart CV Importer Flow

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/resumes` | Optional | Authenticated user's paginated resumes (`?q=`, `?per_page=`), or guest resumes via `?uuids=`. |
| `POST` | `/api/v1/resumes/import` | None | Parses uploaded `cv_file` (`pdf, docx, txt, md, json`, max 5 MB) OR raw `cv_text` (max 60,000 chars) and returns `{ success, raw_text, parsed, stats }`. |
| `POST` | `/api/v1/resumes` | Optional | Create or update (if `uuid` passed). Accepts JSON or `multipart/form-data` (`content` as array or JSON string, optional `photo_data` string or `photo_file` image upload, `template_style`, `accent_color`, `font_family`, `density`, `title`). |
| `GET` | `/api/v1/resumes/{uuid}` | Optional | Load full resume JSON (returns `403` if owned by another user). |
| `PUT` / `PATCH` | `/api/v1/resumes/{uuid}` | Optional | Update resume by `uuid`. |
| `DELETE` | `/api/v1/resumes/{uuid}` | Bearer | Deletes resume owned by authenticated user. |
| `GET` | `/api/v1/resumes/{uuid}/pdf` | None | Downloads crisp A4 PDF (`application/pdf`). Add `?stream=1` for inline preview. |
| `GET` | `/api/v1/public/resumes/{uuid}` | None | Public shareable resume JSON (mirrors `/resume/{uuid}`). |

---

## 6. PDF Converter Suite (All 10 Tools)

Endpoint: `POST /api/v1/convert/{tool}` (No authentication required; returns binary file with `Content-Type` and `X-Converted-Filename`, or JSON when `pdf-to-jpg` uses `output=json`).

### Convert TO PDF
1. **`jpg-to-pdf`**
   - Upload: `files[]` (1–30 `jpg,jpeg,png,webp,gif`, max 15 MB each) or single `file`
   - Options: `page_size` (`a4` | `letter` | `fit`), `orientation` (`auto` | `portrait` | `landscape`), `margin` (`none` | `small` | `normal`)
2. **`word-to-pdf`**
   - Upload: `file` (`docx,doc,rtf,odt,txt`, max 20 MB)
   - Options: `page_size` (`a4` | `letter`)
3. **`powerpoint-to-pdf`**
   - Upload: `file` (`pptx`, max 25 MB)
4. **`excel-to-pdf`**
   - Upload: `file` (`xlsx,xls,csv,ods`, max 20 MB)
   - Options: `orientation` (`landscape` | `portrait`), `page_size` (`a4` | `letter`), `gridlines` (`1` | `0`)
5. **`html-to-pdf`**
   - Input: `file` (`html,htm,txt`, max 10 MB) **OR** `html_code` **OR** `url`
   - Options: `page_size` (`a4` | `letter`), `orientation` (`portrait` | `landscape`)

### Convert FROM PDF
6. **`pdf-to-jpg`**
   - Upload: `file` (`pdf`, max 25 MB)
   - Options:
     - `scale`: `1.5` | `2.0` | `2.75` (default `2.0`)
     - `quality`: `30..100` (default `90`)
     - `max_pages`: `1..50` (default `40`)
     - `output`:
       - `zip` (default) → returns `application/zip` containing all rendered `.jpg` pages
       - `jpg` → returns single `image/jpeg` (uses `page`, defaults to `1`)
       - `auto` → returns `image/jpeg` if 1 page, `application/zip` if multiple pages
       - `json` → returns `{ success, tool, num_pages, rendered_count, pages: [{ page_number, filename, width, height, mime, size, data_uri, base64 }] }`
     - `page`: `1..N` (specific page number when `output=jpg`)
7. **`pdf-to-word`**
   - Upload: `file` (`pdf`, max 25 MB)
   - Options: `mode` (`editable` | `visual`, default `editable`)
8. **`pdf-to-powerpoint`**
   - Upload: `file` (`pdf`, max 25 MB)
   - Options: `mode` (`visual` | `editable`, default `visual`)
9. **`pdf-to-excel`**
   - Upload: `file` (`pdf`, max 25 MB)
   - Options: `merge_sheets` (`per_page` | `single_sheet`, default `per_page`)
10. **`pdf-to-pdfa`**
    - Upload: `file` (`pdf`, max 25 MB)
    - Options: `conformance` (`1b` | `3b`, default `1b`)
