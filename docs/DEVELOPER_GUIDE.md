# e-Matatu Developer Guide

`e-Matatu` is a Laravel-based fleet operations platform for managing matatus, crew, inspections, maintenance, insurance, and route assignments from a single dashboard. The project combines a custom operations UI with Laravel authentication, profile management, and a Supabase-backed fleet database.

The application is designed around day-to-day fleet coordination:
- assign matatus to real Nairobi routes
- assign drivers to vehicles and routes
- view inspection reports and fleet status
- manage insurance policies and maintenance visibility
- maintain user accounts through a styled profile experience

## What The System Does

The app provides a multi-page dashboard for transport operations teams. Instead of keeping everything on one static admin page, the system separates major workflows into dedicated views:

- `Dashboard`
  Shows high-level fleet counts, recent inspections, crew snapshots, and route assignment summaries.
- `Matatus`
  Displays vehicles, route assignments, assigned drivers, and route-selection forms.
- `Crew`
  Shows driver records, driver-to-route assignments, and driver-to-vehicle links.
- `Inspections`
  Lists inspection reports, pass/fail outcomes, and filter controls by vehicle and result.
- `Maintenance`
  Displays maintenance jobs and current maintenance state.
- `Insurance`
  Lists insurance records and includes a form to add new policies.
- `Profile`
  Lets a signed-in user update their profile, change password, and manage account deletion inside the same dashboard visual system.

## Key Features

### 1. Dashboard Operations View

The dashboard summarizes the live fleet dataset and is intended for quick operational monitoring.

It currently shows:
- total matatus
- active vehicles
- total crew members
- active drivers
- pending maintenance count
- insurance policies nearing expiry
- recent inspection records
- recent crew records
- route assignment summaries

### 2. Route-Aware Fleet Management

The system supports route assignment for vehicles and drivers. Route labels are aligned with the Digital Matatus reference map for Nairobi transport routes.

Capabilities include:
- assigning a route to a vehicle
- assigning a driver to a route
- assigning a driver to a specific vehicle
- showing assigned route labels in both `Matatus` and `Crew`

The route catalog configuration is stored in:
- [config/digital_matatus.php](../config/digital_matatus.php)

### 3. Inspection Reporting

Inspection functionality includes:
- listing inspection records from the database
- displaying pass/fail status clearly
- filtering inspection reports by vehicle
- filtering inspection reports by result
- surfacing recent inspections on the main dashboard

### 4. Insurance Management

The system includes:
- a dedicated insurance page
- a database-backed form for adding policies
- policy expiry visibility
- dashboard metrics for policies expiring soon

### 5. Styled Profile Management

The profile page has been redesigned to match the main dashboard theme instead of using the default Jetstream appearance. It now includes:
- profile information form
- password update form
- account deletion form
- traffic-light visual motif reused from the original UI

### 6. Synthetic Demo Data Support

Synthetic fleet records were added to support development, testing, and dashboard visibility. The current seeded dataset includes additional sample:
- drivers
- vehicles
- inspections
- insurance policies

This helps ensure the navigation pages show realistic records even during demo or development sessions.

## Visual Design Direction

The current UI blends:
- the login-page teal and gold palette
- the original dashboard traffic-light motif
- card-based management layouts
- table-heavy operational views for quick scanning

The shared dashboard shell lives in:
- [resources/views/dashboard/layout.blade.php](../resources/views/dashboard/layout.blade.php)

The main styling for dashboard pages lives in:
- [public/css/dashboard-pages.css](../public/css/dashboard-pages.css)

## Technology Stack

### Backend
- PHP 8.2
- Laravel 11
- Laravel Fortify
- Laravel Jetstream
- Laravel Sanctum
- Laravel Socialite
- Livewire
- Volt

### Frontend
- Blade templates
- Tailwind CSS
- Alpine.js
- Vite
- Boxicons

### Data Layer
- Local SQLite for default Laravel app/auth data
- Supabase Postgres for fleet domain tables
- Redis for optional fleet snapshots of records and migration metadata

## Database Model Strategy

The project currently uses two logical data areas:

### 1. Application/Auth Data

Laravel auth and general app-level user flow are still tied to the default database connection.

This includes:
- users
- password reset tokens
- sessions

### 2. Fleet Domain Data

Fleet-related models are configured to use the `supabase` connection directly.

This includes:
- drivers
- vehicles
- routes
- inspections
- insurance
- maintenance

This split lets the dashboard pull operational records from Supabase while keeping the broader Laravel auth structure intact.

### 3. Redis Snapshot Data

Redis is not used as the migration engine. Instead, the project can copy fleet records plus migration metadata into a dedicated Redis database after the SQL migration step completes.

Use:

```bash
php artisan supabase:migrate --seed --redis
```

Optional:

```bash
php artisan redis:sync-fleet --flush
```

The Redis snapshot stores:
- per-table JSON snapshots for fleet records and users
- one JSON blob containing all records for each dataset
- migration filenames from `database/migrations`
- applied migration metadata from the configured SQL connections

## Main Functional Files

### Controllers
- [app/Http/Controllers/DashboardController.php](../app/Http/Controllers/DashboardController.php)
  Central dashboard controller for counts, section pages, assignments, and insurance creation.
- [app/Http/Controllers/ProfileController.php](../app/Http/Controllers/ProfileController.php)
  Handles profile edit, update, and account deletion.

### Models
- [app/Models/Driver.php](../app/Models/Driver.php)
- [app/Models/Vehicle.php](../app/Models/Vehicle.php)
- [app/Models/Route.php](../app/Models/Route.php)
- [app/Models/Inspections.php](../app/Models/Inspections.php)
- [app/Models/Insurance.php](../app/Models/Insurance.php)
- [app/Models/Maintenance.php](../app/Models/Maintenance.php)

### Routes
- [routes/web.php](../routes/web.php)
  Defines dashboard navigation pages, profile routes, and form actions for route assignment and insurance creation.

### Views
- [resources/views/adminpanel.blade.php](../resources/views/adminpanel.blade.php)
- [resources/views/dashboard/matatus.blade.php](../resources/views/dashboard/matatus.blade.php)
- [resources/views/dashboard/crew.blade.php](../resources/views/dashboard/crew.blade.php)
- [resources/views/dashboard/inspections.blade.php](../resources/views/dashboard/inspections.blade.php)
- [resources/views/dashboard/maintenance.blade.php](../resources/views/dashboard/maintenance.blade.php)
- [resources/views/dashboard/insurance.blade.php](../resources/views/dashboard/insurance.blade.php)
- [resources/views/profile/edit.blade.php](../resources/views/profile/edit.blade.php)

## Current Data Flow

At a high level:

1. Users authenticate through Laravel.
2. Dashboard and fleet pages call `DashboardController`.
3. Fleet models query Supabase-backed domain tables.
4. The controller aggregates counts and records.
5. Blade views render metrics, tables, forms, and assignment states.

The dashboard also includes a visible warning state if the fleet connection cannot load from Supabase, so the UI does not fail silently when fleet data is unavailable.

## Route Assignment Persistence

To support route-aware vehicle workflows, the project includes a migration that adds route catalog fields and vehicle route assignment support:

- [database/migrations/2026_04_01_120000_add_route_catalog_columns_and_vehicle_route_assignment.php](../database/migrations/2026_04_01_120000_add_route_catalog_columns_and_vehicle_route_assignment.php)

This migration adds:
- `routes.route_code`
- `routes.route_name`
- `routes.source_label`
- `vehicles.route_id`

## Authentication And Profile Notes

The project uses Laravel auth with:
- Fortify
- Jetstream
- Sanctum
- Google sign-in support via Socialite

Relevant files include:
- [app/Http/Controllers/Auth/GoogleController.php](../app/Http/Controllers/Auth/GoogleController.php)
- [app/Services/Supabase/SupabaseAuthService.php](../app/Services/Supabase/SupabaseAuthService.php)

## Local Development Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js and npm
- SQLite
- Supabase project credentials for fleet data access

### Install

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### Configure Environment

Set the Laravel app basics in `.env`, then configure:

```env
DB_CONNECTION=sqlite

SUPABASE_URL=...
# For most Laravel app traffic, use the Supabase Session pooler string from Project > Connect.
# The direct db.<project-ref>.supabase.co host uses IPv6 by default.
SUPABASE_DB_HOST=...
SUPABASE_DB_PORT=5432
SUPABASE_DB_DATABASE=postgres
SUPABASE_DB_USERNAME=postgres
SUPABASE_DB_PASSWORD=...
SUPABASE_DB_SCHEMA=public
SUPABASE_DB_SSLMODE=require

REDIS_HOST=...
REDIS_PORT=6379
REDIS_PASSWORD=...
REDIS_FLEET_DB=2
REDIS_FLEET_PREFIX=matutu_system_fleet
```

Note:
- Laravel auth uses the default connection.
- Fleet models use the `supabase` connection directly.
- If the app shows `could not translate host name` for `db.<project-ref>.supabase.co`, the machine is usually on an IPv4-only network. Replace the direct connection string with the Supavisor Session pooler string from Supabase `Connect`.

### Run The App

```bash
php artisan serve
npm run dev
```

## Build Commands

```bash
npm run dev
npm run build
```

## Current Strengths

- multi-page operations dashboard instead of a single static admin screen
- real route-aware fleet workflow support
- Supabase-backed fleet data visibility
- synthetic demo records for development and presentations
- unified design language across dashboard and profile pages

## Current Limitations

- pushing changes, running artisan commands, and browser verification depend on the local machine having PHP, GitHub CLI, and proper credentials installed
- auth data and fleet data are currently split across different database connections
- some legacy files from earlier static versions of the project still exist in the repository even though the main dashboard now uses the newer Blade views

## Project Purpose

This project is intended as a practical fleet management system for matatu operations, not just a generic Laravel starter. It focuses on the operational realities of transport management:
- vehicles
- routes
- drivers
- inspections
- maintenance
- insurance
- account management

The goal is to give managers a single system for monitoring fleet health, assigning operational resources, and reviewing compliance-related data through a consistent dashboard interface.
