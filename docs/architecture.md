# CRM Architecture

This project is a Laravel 11, React 19, TypeScript, and Inertia CRM. Laravel owns routing, validation, session state, authorization context, persistence, and queued work. React renders Inertia pages from server-provided props and keeps client state intentionally small.

## System boundaries

- **Backend runtime:** Laravel with PHP 8.2+, Fortify session authentication, Passport API support, Form Requests, Eloquent models, services, actions, jobs, and console commands.
- **Frontend runtime:** React and TypeScript served through Inertia. Page components live under `resources/js/pages` and feature modules live under `resources/js/Features`.
- **Persistence:** MySQL is the source of truth. Redis is used by the application stack for cache, queues, and session-oriented infrastructure where configured.
- **Delivery:** Docker and Nginx support production deployment; GitHub Actions provide CI, linting, and tests.

## Request and response flow

1. A browser request enters Laravel through `routes/web.php` or a settings route in `routes/settings.php`.
2. Authenticated CRM pages are protected with the `auth` and `verified` middleware group.
3. The route invokes an Inertia page controller such as `DashboardController`, `CustomerController`, `ModulePageController`, `ProfileController`, or `SecurityController`.
4. The controller loads models or delegates reusable work to services/actions.
5. Page responses use `Inertia::render(...)` with props; form submissions usually redirect back to a named route with flash data.
6. Inertia resolves the matching React page, passes the props to the component tree, and preserves Laravel as the source of server state.

The customer create flow is the canonical example: `GET /customers/create` renders the customer form with companies, the form posts to `CustomerController::store`, `CustomerRequest` validates the payload, Eloquent creates the record, `ActivityLogger` records the audit event, and the user is redirected to the index with a success flash message.

## Backend organization

### Controllers

Controllers are request routers, not API resource classes. They should stay thin, use Form Requests for validation, and return Inertia responses or redirects rather than raw JSON for web pages.

- `app/Http/Controllers/CRM/DashboardController.php` renders dashboard metrics from `DashboardService`.
- `app/Http/Controllers/CRM/CustomerController.php` owns the customer list, create form, and store flow.
- `app/Http/Controllers/CRM/ModulePageController.php` renders placeholder CRM module pages for schema-ready modules.
- `app/Http/Controllers/Settings/*` owns profile, password, and security settings pages.

### Requests and validation

Server validation belongs in `app/Http/Requests`. Client-side validation may mirror server rules with Zod schemas for better UX, but server validation remains authoritative.

- `app/Http/Requests/CRM/CustomerRequest.php` validates customer creation fields.
- `app/Http/Requests/Settings/*` validates profile, password, deletion, and two-factor workflows.
- `app/Concerns/PasswordValidationRules.php` and `app/Concerns/ProfileValidationRules.php` centralize reusable validation rules.

### Actions and services

Actions contain domain operations that should be reusable outside a single controller. Services contain reusable orchestration or aggregation logic.

- `app/Actions/Fortify/*` contains Fortify registration and password reset behavior.
- `app/Actions/Invoices/MarkOverdueInvoices.php` supports scheduled invoice status maintenance.
- `app/Services/DashboardService.php` aggregates dashboard metrics and caches them briefly.
- `app/Services/ActivityLogger.php` writes audit trail entries for model changes.

### Models and data relationships

Core CRM entities are modeled with Eloquent under `app/Models`: `Customer`, `Company`, `Product`, `Quote`, `Invoice`, `Payment`, `Task`, `Note`, `ActivityLog`, `Setting`, `Role`, `Permission`, and `User`.

Customer-centered relationships drive most CRM flows: customers can belong to companies, be owned by users, and connect to invoices, quotes, tasks, notes, and activity log entries. New domain behavior should start at the model relationships and then expose only the required page flow through controllers and Inertia props.

### Jobs and scheduled work

Queued jobs live under `app/Jobs`. Current job placeholders/implementations include report export and invoice PDF generation. Scheduled or command-line maintenance should call actions/services rather than duplicating controller logic.

## Frontend organization

### Inertia pages

Route-level pages are in `resources/js/pages`. Authentication pages, settings pages, the dashboard, the welcome page, and generic CRM module pages are organized by route area.

### Feature modules

Feature-specific frontend code should live under `resources/js/Features/<Feature>`. The Customers module demonstrates the expected shape:

- `pages/` for Inertia page components.
- `schemas/` for Zod validation schemas that mirror server rules.
- `types/` for feature-specific TypeScript interfaces.
- `components/`, `hooks/`, and `services/` for feature-specific expansion.

Shared UI belongs in `resources/js/components`, Shadcn-style primitives belong in `resources/js/components/ui`, reusable hooks belong in `resources/js/hooks`, global types belong in `resources/js/types`, formatting utilities belong in `resources/js/Utils`, and minimal cross-page client state belongs in `resources/js/Stores`.

### State management

The app intentionally avoids Redux-style global data duplication. Use these rules:

- Prefer Laravel database/session state delivered through Inertia props.
- Use React local state for page-only UI state.
- Use the existing lightweight store pattern only for cross-component UI state that cannot be represented cleanly as props.
- Use flash data from redirects for success/error messages and consume it through the flash toast hook.

## Routing map

- `/` renders the public welcome page.
- `/dashboard` renders dashboard metrics through `DashboardController`.
- `/customers` supports customer index, create, and store routes through `CustomerController`.
- `/{module}` renders placeholder pages for users, roles, permissions, companies, products, services, quotes, invoices, payments, tasks, notes, and activity logs.
- `/settings/profile`, `/settings/security`, `/settings/password`, and `/settings/appearance` provide authenticated settings flows.
- `/.well-known/passkey-endpoints` intentionally returns JSON because it is a WebAuthn/passkey discovery endpoint, not an Inertia page.

## Adding a CRM feature

1. Add a new migration rather than editing deployed migrations.
2. Add or update the Eloquent model and relationships.
3. Add a Form Request for write operations.
4. Add an action or service when logic is reusable, scheduled, or too large for a thin controller.
5. Add a CRM controller that returns Inertia pages and redirects.
6. Register routes in `routes/web.php` or a scoped route file if the feature grows.
7. Add TypeScript feature types, Zod schemas, and Inertia pages under `resources/js/Features/<Feature>`.
8. Reuse layouts and shared UI components instead of creating feature-specific shells.
9. Add feature tests for controller behavior and validation.

## Constraints and hotspots

- Do not move business logic into controllers.
- Do not bypass Form Requests for mutating form submissions.
- Do not return raw JSON from Inertia page controllers; reserve JSON for protocol/API endpoints.
- Do not modify authentication or security validation without tests.
- Do not touch production Docker files without DevOps review.
- Do not remove models or tables without a full deprecation plan.
- Do not modify existing deployed migrations; add forward migrations instead.

## Verification checklist for maintainers

A change is architecturally aligned when a reviewer can trace browser request → Laravel route → controller → request validation/action/service → model/database → Inertia props → React page, identify both server and client validation, find or add tests, and confirm that shared UI and domain logic were placed in reusable locations.
