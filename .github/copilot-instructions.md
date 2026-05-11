# Task Orbit — Project Instructions

## What This App Does

Task Orbit is a Turkish internship management platform. It tracks companies, internships, interns, lessons, tasks, task submissions, and attendance. It integrates with Google Calendar (syncing lessons and tasks) and Telegram (sending attendance-check messages).

## Architecture

The app has two distinct API surfaces:

- **REST API** (`routes/api.php`) — JSON responses consumed by external clients or mobile. No Inertia.
- **Inertia web** (`routes/web.php`) — Server-rendered SPA via Inertia + React. Controllers return `Inertia::render()`.

Never mix the two: REST controllers return `response()->json(...)`, Inertia controllers return `Inertia::render(...)`.

## API Response Shape

All REST API responses must follow this exact structure:

```php
return response()->json([
    'status'  => 'success',  // or 'error'
    'message' => 'Human-readable message',
    'data'    => $payload,
], $httpStatusCode);
```

Never return bare arrays or models from API controllers.

## Key Domain Models

| Model | Table | Notable relationships |
|---|---|---|
| `Task` | `tasks` | belongsTo `Lesson`; morphMany `Image`, `Commantable`; hasMany `TaskSubmission` |
| `Lesson` | `lessons` | belongsTo `Internship`; hasMany `Task`, `Attendance` |
| `Internship` | `internships` | belongsTo `Company`; hasMany `Lesson`, `InternRegister` |
| `Company` | `companies` | hasMany `Internship`, `Profile` |
| `User` | `users` | managed by Fortify |

Several models use polymorphic `images` and `commantables` — check existing models before adding new relations.

## Frontend Conventions

- Pages live in `resources/js/pages/`. Use lowercase kebab-case filenames (e.g., `task-list.tsx`).
- Reuse components from `resources/js/components/` before creating new ones.
- UI strings are in **Turkish**.
- Always import route helpers from `@/routes` or `@/actions` (Wayfinder). Never hardcode URLs.
- Layouts live in `resources/js/layouts/`; check existing layouts before creating a new one.

## Integrations

- **Google Calendar** — `GoogleCalendarService`. Synced via `SyncLessonToCalendar` and `SyncTaskToCalendar` jobs, triggered by `LessonObserver` / `TaskObserver`.
- **Telegram** — `SendTelegramMessage` job; triggered via `LessonController::sendAttendanceCheck`.

Do not bypass observers when creating/updating Lesson or Task — they fire the sync jobs.

## Testing

- Tests use **Pest v4**. `RefreshDatabase` is applied globally to all Feature tests via `tests/Pest.php`.
- Run: `php artisan test --compact` — always filter to the affected file when possible.
- Every code change must have a corresponding test. Prefer updating an existing test over writing a new one.
- Use factories and their states; do not manually set model attributes when a factory state exists.

## Code Quality

- Run `vendor/bin/pint --dirty --format agent` after every PHP file change.
- Wayfinder types are generated with `php artisan wayfinder:generate` — run it after adding or renaming routes.
- Use `php artisan make:` commands to scaffold new files; never create PHP class files by hand.
