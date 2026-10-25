# Frontend architecture

The Vue templates are adapters to an object-oriented application layer. The Vue `setup()` hook only creates a `CabinetViewModel` and exposes its bindings to the template.

## Responsibilities

- `domain.js` — stateless domain rules in `FinanceDomain`.
- `core/` — API transport and browser history.
- `state/` — reactive cabinet data in `CabinetStore`.
- `services/` — shared toast notification service.
- `controllers/` — focused dashboard, transaction, dialog, task, exchange, authentication, and Telegram controllers.
- `presentation/` — `CabinetViewModel` plus one file per icon, select, and modal component controller.
- `app.js` — page template and thin Vue adapter.

Controllers receive their collaborators through constructors. API transport, browser history, state, notifications, and page-specific actions have separate responsibilities. Add new domain behavior to the relevant controller or service instead of expanding the Vue template adapter.

The build script assembles the class files into `public/material/application.js`. `app.blade.php` loads it after `domain.js` and before `app.js`.
