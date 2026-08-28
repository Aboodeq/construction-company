# Construction Company Platform

A Laravel-based website and admin panel for a construction company: services, projects/portfolio,
blog, testimonials, quote requests, bookings, and an internal admin panel for managing all of it.

The public site and the admin panel are both Arabic-first and right-to-left (RTL).

## Stack

- **Laravel 13** / PHP 8.3
- **Blade + Alpine.js + Tailwind CSS v4** — server-rendered views, no SPA framework
- **spatie/laravel-permission** — role- and permission-based access control for the admin panel
- **spatie/laravel-sluggable** — automatic slugs for services, projects, blog posts, categories
- **spatie/laravel-sitemap** — sitemap generation
- **intervention/image** — image processing for uploads
- **Pest** — testing

## Getting started

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

By default `.env.example` uses SQLite for a zero-config start:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

For local development closer to production, switch `DB_CONNECTION` to `mysql` and provide real
credentials before migrating — this is what the checked-in `.env` for this project actually uses
(database `construction_company`). SQLite stays the driver for the automated test suite regardless
of what `.env` points at — see `phpunit.xml`, which pins `DB_CONNECTION=sqlite` /
`DB_DATABASE=:memory:` for every test run.

```bash
composer run dev
```

This runs the PHP server, queue listener, log tailer (`pail`), and Vite dev server together.

## Admin panel

The admin panel lives at a configurable prefix, set via `ADMIN_PATH` in `.env` (defaults to
`office-panel`). Only users with the `admin` or `editor` role (see
`database/seeders/RolePermissionSeeder.php`) and an active account can enter it — there is no
public self-registration by design; accounts are created by an administrator.

Seed a default admin account with:

```bash
php artisan db:seed --class=AdminUserSeeder
```

This creates `admin@construction-company.test` / `password` with the `admin` role. **Change this
password before deploying anywhere real.**

## Locale

The application locale is Arabic (`APP_LOCALE=ar`). Framework-level strings (validation messages,
auth/password-reset messages, pagination) are translated in `lang/ar/`. Admin-panel and public-site
copy is written directly in Arabic in the Blade views rather than routed through `lang/` files —
this codebase does not currently support a second language; see the project roadmap if that
changes.

## Testing

```bash
php artisan test
```

## Code style

```bash
vendor/bin/pint
```
