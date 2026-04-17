# RDM Developments

Marketing + lead-gen website for **RDM Developments**, a Pretoria East building & renovation business owned and operated by Ruben Metcalfe.

Built with **Laravel 11**, **Tailwind CSS**, **Alpine.js** and **Filament v3** (admin).

---

## Features

- Home, Services, Projects, About, Contact pages — all SEO-optimised
- **Services** module with rich descriptions, per-page SEO title & meta description
- **Projects** module with two modes:
  - **Renovations** — draggable before/after comparison slider
  - **Completed builds** — clean photo gallery
  - Images are categorised as `before`, `after` or `gallery` in the admin
- **Enquiry system** — form on every page, stores to DB, emails the owner
- Floating WhatsApp button + click-to-call everywhere
- Sticky mobile-first header, full responsive layout
- Auto-generated `/sitemap.xml` and `/robots.txt`
- JSON-LD `GeneralContractor` structured data for local SEO
- **Filament admin** at `/admin` to manage services, projects, images and enquiries

## Stack

| Layer | Tech |
|---|---|
| Framework | Laravel 11 (PHP 8.2+) |
| Frontend  | Blade + Tailwind CSS 3 + Alpine.js + Vite |
| Admin     | Filament v3.3 |
| Database  | MySQL |
| Mail      | Log driver in dev; configurable SMTP in prod |

## Local development

```bash
# install deps
composer install
npm install

# environment
cp .env.example .env
php artisan key:generate

# database (create 'rdmdev' in MySQL first, or switch DB_CONNECTION=sqlite)
php artisan migrate --seed

# create yourself an admin user
php artisan make:filament-user

# link storage for uploaded images
php artisan storage:link

# build assets
npm run build      # or `npm run dev` for hot reload

# serve
php artisan serve --port=8097
```

Then visit:
- `http://localhost:8097` — public site
- `http://localhost:8097/admin` — Filament admin

## Environment variables

The usual Laravel `.env` keys, plus brand/contact config:

```dotenv
RDM_PHONE="072 972 9393"
RDM_PHONE_TEL="+27729729393"
RDM_WHATSAPP="27729729393"
RDM_EMAIL="ruben@rdmdev.co.za"
RDM_ENQUIRY_TO="ruben@rdmdev.co.za"
RDM_OWNER="Ruben Metcalfe"
RDM_LOCATION="Pretoria East, Gauteng"
```

These are consumed via `config/rdm.php`.

## Deployment

1. Provision PHP 8.2+ with extensions: `curl`, `zip`, `gd`, `fileinfo`, `pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `exif`, `intl`
2. `composer install --no-dev --optimize-autoloader`
3. `npm ci && npm run build`
4. Copy `.env.example` → `.env`, fill in values, `php artisan key:generate`
5. `php artisan migrate --force`
6. `php artisan db:seed --force` (first deploy only)
7. `php artisan make:filament-user`
8. `php artisan storage:link`
9. `php artisan config:cache route:cache view:cache`
10. Point web root at `public/`

## Licence

Proprietary — © RDM Developments. All rights reserved.
