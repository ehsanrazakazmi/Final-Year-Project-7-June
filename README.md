# GharBar — Home Services Portal

A Laravel application for booking home-maintenance services, built around three
separate portals — **administrator**, **resident** and **technician** — each
with its own layout, navigation and access rules. A companion React Native app
consumes the same backend over a REST API.

Originally a final-year project; now maintained as a portfolio piece.

---

## What it does

Residents browse a catalogue of services (plumbing, electrical, carpentry,
cleaning), pick an **availability window** — a named time slot such as
*subah 05:00–09:00* — and place an order. Administrators manage the catalogue,
the availability windows and the user base. Technicians see the jobs assigned to
their category.

| Portal | Path | Who |
|---|---|---|
| Admin | `/adminpanel` | role 1 |
| Resident | `/pages/home` | role 2 |
| Technician | `/technicianpanel` | role 3 |

---

## Architecture notes

**Roles.** Authorisation uses [spatie/laravel-permission][spatie] with exactly
three seeded roles (`admin`, `resident`, `technician`) — there is deliberately
no UI to create or delete roles. A legacy integer `users.role` column is kept in
sync alongside the Spatie role, because the REST API returns raw `users` rows
and the React Native client switches on that integer.

**One source of truth for routing.** `App\Support\Portals` maps a role to its
landing path. The three role middleware (`Admin`, `resident`, `technician`) all
extend `App\Http\Middleware\EnsureRole`, which resolves through the same helper
— so the guard and the redirect target can never disagree and loop.

**Account creation is admin-only in spirit.** An administrator creates a user
with a name, an email and a role; the new user receives an invitation email and
chooses their own password. Administrators never see or set passwords. Public
self-registration still exists but is hard-locked to the `resident` role.

**Invitation tokens** use a dedicated `invitations` password broker with a
seven-day expiry, separate from the 60-minute password-reset broker.

---

## Requirements

- PHP 8.1+ (developed on 8.2)
- MySQL / MariaDB 10.4+
- Composer, Node.js and npm

## Setup

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
# set DB_DATABASE / DB_USERNAME / DB_PASSWORD in .env

php artisan migrate --seed
npm run build            # required - see "Assets" below

php artisan serve
```

Seeding creates the three roles, four service categories, and one administrator.
**The admin password is generated randomly and printed once** to the console:

```
Admin created
  email    : admin@gharbaar.local
  password : eEZwlyw8
  Shown once - copy it now.
```

### Assets

Several layouts use `@vite(...)`. If neither a Vite dev server nor a production
build is present, those pages fail with *"Vite manifest not found"*. Either run
`npm run dev` while developing, or `npm run build` once. The build is not
committed, so a fresh clone needs it.

### Serving it properly

`php artisan serve` is safe by default — it binds to `127.0.0.1` only. If you
serve the project through Apache instead, point the document root at
**`public/`**, not at the project root. Serving the project root exposes `.env`,
`.git/` and `storage/logs/` as downloadable static files.

---

## Tests

```bash
php artisan test           # or: vendor/bin/phpunit
```

Tests run against MySQL (see `phpunit.xml`) so they exercise the same SQL
dialect as production — the admin dashboard uses `YEAR()`, which SQLite lacks.
Create the database once:

```sql
CREATE DATABASE gbsb_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Coverage focuses on the parts that are easy to break silently:

| Suite | What it pins down |
|---|---|
| `Auth/PortalRedirectTest` | each role lands on its own portal, is bounced out of the others, and an unrecognised role is signed out rather than looping |
| `Auth/RegistrationTest` | a forged `role` in the signup request cannot create an administrator |
| `Api/TableAllowlistTest` | `/api/tables/{table}` serves catalogue tables only; no password hash is reachable |
| `AvailabilityRelationTest` | the service ↔ availability pivot stores its two foreign keys the right way round |

That last one exists because `belongsToMany()` with its key arguments reversed
writes swapped ids without raising an error — the kind of bug only a round-trip
assertion catches.

---

## The React Native app

A separate Expo/React Native client logs in through `/api/sanctum/token` and
reads the catalogue, wishlist and order status endpoints.

Two endpoints keep a deliberate backward-compatibility shim, marked
`// COMPATIBILITY:` in `routes/api.php`: the app posts `colors_id` and reads
`item.color.code` / `.code1`, names that predate the availability rename. The
API rebuilds that shape rather than breaking the installed app.

The client's base URL is `http://127.0.0.1:8000`, which only resolves on the
host itself. To run it against a device or emulator, serve with
`php artisan serve --host=0.0.0.0` and point the app at `10.0.2.2:8000`
(Android emulator) or the machine's LAN address.

---

## Known issues

Kept visible rather than hidden:

- **The `items` table is inconsistent.** The migration defines
  `product_id / availability_id / order_id / quantity`, while
  `CheckoutController` writes `service_id` and `category_id`, and
  `TechnicianController::dashboard()` plus `/api/tech_orders` query
  `items.category_id`. Checkout and the technician dashboard therefore fail
  against the real schema. Marked with `markTestIncomplete()` in
  `Auth/PortalRedirectTest`.
- **Most API routes are unauthenticated.** Only `/api/user` requires a Sanctum
  token. Closing the rest requires the mobile client to start sending its token.
- **Email verification is not enforced** on the three portals — only on `/home`.
- **`GET /logout`** sits inside the admin middleware group, so only
  administrators can use it. `POST /logout` works for everyone.
- `App\Models\Cart` and `App\Models\technician` are stubs with no backing table.

[spatie]: https://spatie.be/docs/laravel-permission
