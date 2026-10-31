# Clinton Agburum — clintonagburum.com

Personal website for Clinton Agburum: founder, technical lead, product builder. Laravel + Blade + Tailwind, with a small single-admin CMS for Products, Writing and article Categories.

## Stack

- Laravel 13, Blade, MySQL/Eloquent
- Tailwind CSS v4 + `@tailwindcss/typography`, built via Vite
- Alpine.js (minimal — mobile nav only)
- Tiptap (vanilla, framework-agnostic) for the admin article editor; articles are stored as Tiptap JSON and rendered server-side through `app/Support/TiptapRenderer.php` — a small whitelist renderer, not the Tiptap JS package, so the public site never ships the editor bundle
- Hand-rolled session auth (no Breeze/Fortify) — single admin account, no public registration, login rate-limited and optionally protected by Cloudflare Turnstile

## Local setup

```
composer install
npm install
cp .env.example .env   # then fill in DB_*, ADMIN_EMAIL / ADMIN_PASSWORD and ADMIN_PATH
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build           # or `npm run dev` while working on views/assets
php artisan serve
```

Requires a MySQL database matching `.env`'s `DB_DATABASE` to already exist (`CREATE DATABASE clinton_website;`).

The `AdminUserSeeder` creates (or updates) the one admin account from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env` — nothing is hardcoded. Once logged in, change the password from the admin's **Password** page; re-running `php artisan db:seed --class=AdminUserSeeder` also resets it to whatever `.env` holds.

### Admin-related `.env` settings

- `ADMIN_PATH` — the URL segment the admin area lives under (`/{ADMIN_PATH}/login`, `/{ADMIN_PATH}/articles`, …), configured in `config/admin.php`. Defaults to `admin`; in production set it to something private and hard to guess, and never commit the real value. Route names (`admin.login`, `admin.overview`, …) don't change, so always link with `route()` rather than hardcoding the path.
- `TURNSTILE_SITE_KEY` / `TURNSTILE_SECRET_KEY` — Cloudflare Turnstile on the login form (keys from Cloudflare dashboard → Turnstile). Leave both blank to disable it entirely — no widget, no verification — which is the normal setup for local dev and tests.

## Structure

- `app/Models/Product.php`, `app/Models/Article.php` — the two content types; `app/Models/Category.php` groups articles (each article belongs to one category)
- `app/Support/TiptapRenderer.php` — safe Tiptap JSON → HTML rendering (public article page + admin preview both use it)
- `app/Http/Controllers/Admin/*` — the CMS under `/{ADMIN_PATH}` (overview, products, articles, categories, article image upload, change password)
- `app/Http/Controllers/AuthController.php` + `app/Rules/ValidTurnstileToken.php` — admin login/logout and the server-side Turnstile check
- `app/Http/Controllers/SitemapController.php`, `FeedController.php` — `/sitemap.xml` and the RSS feed at `/feed.xml`
- `app/Http/Controllers/PublicStorageController.php` — serves uploaded article images at `/storage/{path}` when the `public/storage` symlink is missing (see Deployment)
- `resources/views/` — `home`, `work/*`, `writing/*`, `feed`, `sitemap` (public), `admin/*` (CMS), `components/*` (shared Blade components incl. `<x-seo>`)
- `resources/js/admin-editor.js` — the Tiptap editor, loaded only on the article create/edit admin pages
- `routes/web.php` — all routes, public and admin

## Deployment

Standard Laravel hosting: PHP 8.3+, Composer, MySQL, writable `storage/` and `bootstrap/cache/`. No Docker, Redis, queue worker, or Node process required at runtime. The compiled assets in `public/build/` are committed, so the server doesn't need Node at all — run `npm run build` locally and commit the result whenever you change CSS/JS.

This app's document root is Laravel's `public/` directory. The root-level `.htaccess` transparently rewrites everything into `public/` so it also works unmodified on hosts where the domain's document root can't be repointed at `public/` directly (typical shared/cPanel hosting). If your host *can* point the domain straight at `public/`, that's the cleaner setup — the root `.htaccess`'s rewrite block becomes unnecessary then (its canonical-host/HTTPS redirect at the top should stay either way, or move into `public/.htaccess`).

```
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Before the first deploy, set `ADMIN_PATH` and the Turnstile keys in the server's `.env`. Because routes are cached, re-run `php artisan config:cache && php artisan route:cache` after changing `ADMIN_PATH`.

`public/storage` is gitignored, so a fresh checkout has no symlink until `storage:link` runs (some shared hosts block symlinks entirely). Uploaded article images still work without it: `/storage/{path}` falls through to `PublicStorageController`, which serves `articles/*` images straight from `storage/app/public` with long-lived cache headers and without starting a session, setting cookies or touching the database. With the symlink in place the web server serves the files directly and the route is never hit.
