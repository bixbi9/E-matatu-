# e-Matatu Fleet Management System

`e-Matatu` is a Laravel-based fleet management platform for matatu operations. It helps transport teams manage vehicles, drivers, route assignments, inspections, maintenance visibility, insurance records, and user accounts through a unified dashboard.

## Highlights

- multi-page operations dashboard
- matatu-to-route assignment workflows
- crew and driver assignment management
- inspection reporting and filtering
- insurance policy tracking
- maintenance visibility
- styled profile management
- Supabase-backed fleet data integration

## Main Modules

### Dashboard
- fleet counts and status summaries
- recent inspections
- recent crew records
- route assignment visibility

### Matatus
- view vehicles
- assign routes to matatus
- link drivers to vehicles

### Crew
- view driver records
- assign drivers to routes
- connect drivers to vehicles

### Inspections
- browse inspection reports
- filter by vehicle and result
- review pass/fail outcomes

### Insurance
- view policy records
- add new insurance policies
- monitor upcoming expiries

### Profile
- update account information
- change password
- delete account

## Tech Stack

- Laravel 11
- PHP 8.2
- Blade + Tailwind CSS
- Alpine.js
- Vite
- Laravel Fortify / Jetstream / Sanctum
- Supabase Postgres for fleet domain data

## Data Setup

The project currently uses:
- local Laravel auth/app data
- Supabase for fleet tables such as `drivers`, `vehicles`, `routes`, `inspections`, `insurance`, and `maintenance`
- Redis for optional fleet snapshots after migrations/seeding

This project is built to support real fleet operations workflows for matatu management, not just serve as a generic Laravel starter. It focuses on making transport data easier to view, assign, and maintain in one place.

## Documentation

For the full technical guide, architecture notes, setup details, and key file references, see:

- [Developer Guide](docs/DEVELOPER_GUIDE.md)
