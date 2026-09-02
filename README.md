# Clinton Agburum — clintonagburum.com

Personal website for Clinton Agburum: founder, technical lead, product builder. Laravel + Blade + Tailwind, with a small single-admin CMS for Products and Writing.

## Stack

- Laravel 13, Blade, MySQL/Eloquent
- Tailwind CSS v4 + `@tailwindcss/typography`, built via Vite
- Alpine.js (minimal — mobile nav only)
- Tiptap (vanilla, framework-agnostic) for the admin article editor; articles are stored as Tiptap JSON and rendered server-side through `app/Support/TiptapRenderer.php` — a small whitelist renderer, not the Tiptap JS package, so the public site never ships the editor bundle
- Hand-rolled session auth (no Breeze/Fortify) — single admin account, no public registration

## Local setup

```
composer install
npm install
cp .env.example .env   # then fill in DB_* and ADMIN_EMAIL / ADMIN_PASSWORD
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build           # or `npm run dev` while working on views/assets
php artisan serve
```

Requires a MySQL database matching `.env`'s `DB_DATABASE` to already exist (`CREATE DATABASE clinton_website;`).

The `AdminUserSeeder` creates (or updates) the one admin account from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env` — nothing is hardcoded. Re-run `php artisan db:seed --class=AdminUserSeeder` any time to change the password.

## Structure

- `app/Models/Product.php`, `app/Models/Article.php` — the two content types
- `app/Support/TiptapRenderer.php` — safe Tiptap JSON → HTML rendering (public article page + admin preview both use it)
- `app/Http/Controllers/Admin/*` — the `/admin` CMS (products, articles, image upload)
- `resources/views/` — `home`, `work/*`, `writing/*` (public), `admin/*` (CMS), `components/*` (shared Blade components incl. `<x-seo>`)
- `resources/js/admin-editor.js` — the Tiptap editor, loaded only on the article create/edit admin pages
- `routes/web.php` — all routes, public and admin

## Deployment

Standard Laravel hosting: PHP 8.3+, Composer, MySQL, writable `storage/` and `bootstrap/cache/`. No Docker, Redis, queue worker, or Node process required at runtime — `npm run build` is a build-time step only.

This app's document root is Laravel's `public/` directory. The root-level `.htaccess` transparently rewrites everything into `public/` so it also works unmodified on hosts where the domain's document root can't be repointed at `public/` directly (typical shared/cPanel hosting). If your host *can* point the domain straight at `public/`, that's the cleaner setup — the root `.htaccess`'s rewrite block becomes unnecessary then (its canonical-host/HTTPS redirect at the top should stay either way, or move into `public/.htaccess`).

```
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
