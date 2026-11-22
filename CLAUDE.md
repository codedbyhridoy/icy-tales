# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

IcyTales is an e-commerce storefront plus admin panel (products with variants, session cart, checkout and payments, blog, team members, reviews, newsletter). It is built with Laravel 12 (PHP ^8.2), Inertia.js v2, Vue 3 and Tailwind v4, on MySQL. Local dev runs under Laragon on Windows. Note: README.md still says "Dental Match App", and some commented-out code mentions dental roles (`practice`, `specialist`). Both are left over from the template this project was copied from.

## Commands

```bash
composer dev                 # serve + queue:listen + pail logs + vite, all at once
npm run dev                  # vite only
npm run build                # client build + SSR build (resources/js/ssr.js)
npm run lint / lint:fix      # ESLint on resources/js (ignores Components/ui)
npx prettier --write <file>  # single quotes, organize-imports + tailwind class sorting
vendor/bin/pint              # PHP formatting

php artisan test                                   # Pest (all suites)
php artisan test --filter="test name or file"     # single test
vendor/bin/pest tests/Feature/Auth/AuthenticationTest.php

php artisan migrate --seed   # seeders: Location (BD states/cities CSV), Member, RolePermission, Setting
```

Tests use MySQL database `testing` (see phpunit.xml), not SQLite. That database must exist. `tests/Pest.php` applies `RefreshDatabase` to `Feature` and `Unit/Database` and creates the `super admin` / `admin` roles and permissions before each test. `tests/Feature/ArchTest.php` enforces these rules: no `env()` outside config, classes in `App\Services` must end in `Service`, plus Laravel/security presets.

## Architecture

**Two session guards on one `users` table.** `config/auth.php` defines `web` (customers) and `admin`. Both use the same `User` provider. Which guard applies depends on the URL:
- Admin routes live under `/admin` and use `auth:admin` + `role:super admin|admin` (Spatie permission). Admin login is `admin-login`. Customer login is `login` / `customer.login`.
- `bootstrap/app.php` `redirectGuestsTo` sends guests to the admin login or the customer login based on `admin*` paths and route names.
- `HandleInertiaRequests::share()` picks the guard the same way and shares `auth.user`, `auth.guard`, `auth.role`, and `auth.can` (a permission-name → true map). Check these in Vue instead of re-querying.
- Any new admin feature must follow the same `admin*` path / `admin.*` / `super_admin.*` naming, or the guard detection will pick the wrong guard.

**Routes.** `routes/web.php` holds the storefront and requires `routes/auth.php` and `routes/super_admin.php`. Admin route names start with `super_admin.` (e.g. `super_admin.products.index`); admin profile routes start with `admin.`. `routes/api_v1.php` is mounted at `/api/v1` with name prefix `api.` but is currently empty. API-only pieces (`Filters/V1`, `Http/Resources/V1`, `Policies/V1`, `Traits/ApiResponses`, Scramble docs) exist for it. `AppServiceProvider` resolves policies from `App\Policies\V1\` for `api/v1` routes and from `App\Policies\` otherwise.

**Frontend.** Inertia pages are in `resources/js/Pages/` and are resolved by name (`Inertia::render('Frontend/HomePage')`). They are split into `Frontend/*` (uses `FrontendLayout`), `SuperAdmin/*` (uses `AuthenticatedLayout`), and Breeze `Auth/*` (admin login). Routes are reached through Ziggy's `route()`. `@/` aliases `resources/js`. `Components/ui/` holds generated shadcn-vue components (reka-ui/radix-vue; see `components.json`). Add to it with the shadcn-vue CLI rather than editing by hand. Rich text uses CKEditor. Login and registration forms use Laravel Precognition (`precognitive` middleware).

**Shared props every page receives:** `auth`, `cart` (summary from `CartService`, computed lazily), `ziggy`, `flash.success` / `flash.error`, and from `AppServiceProvider` `appUrl`, `locale`, and `lang` (the full contents of `lang/{locale}.json`). To show a toast, redirect with `->with('success', ...)`.

**Cart → checkout → payment.**
- `CartService` keys the cart on `session()->getId()`, so there are no user carts. Cart items store `price_snapshot` and an options snapshot taken when the item is added.
- `CheckoutController@store` builds the Order, OrderItems, and Payment.
- `cod` orders clear the cart right away. Other gateways go through `PaymentManager::gateway($name)`, which returns an implementation of `App\Contracts\PaymentGateway` (`app/Gateways/*`: Stripe, PayPal, bKash).
- SSLCommerz is handled separately by `SSLCommerzService` + `PaymentController` (success/fail/cancel/ipn callbacks).
- Watch out: `PaymentManager` refers to `PayPalGateway` but the file and class are `PaypalGateway`. This works on Windows and fails on case-sensitive filesystems.

**Admin list filtering.** Models expose `filter()` / `frontendFilter()` scopes that take `search`, `sort_by`, and `sort_type` from the request. They pair with `useSearchAndSort.js` on the frontend.

**Other notes.** Honeybadger reports exceptions. Notifications and mail are queued (`QUEUE_CONNECTION=database`), so run the queue worker (`composer dev` starts one). `api/*` 404 and 401 errors return JSON.


## Working Rules

- Before making changes, inspect the relevant files and understand the existing code.
- Keep the existing design, behavior, and coding patterns. Do not redesign or refactor unrelated parts.
- Make only the changes needed for the requested task.
- Do not modify `.env`, secrets, or database data.
- Never run destructive commands such as `migrate:fresh`, `db:wipe`, or database reset commands without my approval.
- Do not edit files inside `vendor/`. Fix issues through Composer dependencies or project code.
- Before editing, check `git status` and do not overwrite my uncommitted changes.
- Work in small batches. After each batch, explain what changed and run the relevant tests or checks.
- If you find an unrelated issue, report it instead of fixing it automatically.
- If a change could break existing features, explain the risk and ask before proceeding.
- After completing a task, summarize the changed files, tests run, and any remaining issues.