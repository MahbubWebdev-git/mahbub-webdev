# Email verification & mail troubleshooting guide

This document collects the checks, fixes and troubleshooting steps we applied in this project to get email verification working reliably. Keep this as a reference when you see verification or mail delivery issues.

## Purpose

Help you quickly diagnose and fix problems when:
- verification links return 404 or redirect to login
- users are able to register but not required to verify
- "Mailer [1] is not defined" or other mailer errors appear
- verification link click redirects to login and then shows "You must verify your email address before logging in."

## Quick checklist (first things to check)

1. APP_URL matches the host:port your dev server uses (include :8000 if using `php artisan serve`).
2. `MAIL_MAILER` in `.env` is a valid mailer name (e.g. `smtp`, `log`) — not `true` or boolean.
3. Verification routes exist: `verification.notice`, `verification.verify`, `verification.send` (run `php artisan route:list --name=verification.verify`).
4. The `User` model implements `Illuminate\Contracts\Auth\MustVerifyEmail`.
5. Registration flow doesn't auto-login the user if you require verification.
6. Login flow blocks unverified users unless they are logging in while following a verification link.
7. Session driver/persistence: if using database sessions, the `sessions` table must exist and the session cookie must be preserved across redirects.
8. Mail sending: for local testing set `MAIL_MAILER=log` or use `MAIL_MAILER=smtp` and enable a log copy (see `MAIL_LOG` below).

## Files we changed (and why)

- `app/Models/User.php`
  - Implemented `Illuminate\Contracts\Auth\MustVerifyEmail` so Laravel treats users as verifiable.

- `app/Http/Controllers/Auth/RegisteredUserController.php`
  - Removed `Auth::login($user)` after registration so new users are not auto-logged-in before verification.
  - Redirect to `verification.notice` with a session status message.

- `app/Http/Requests/Auth/LoginRequest.php`
  - Block login for unverified users, but allow login to proceed if the user was redirected from a verification URL (session `url.intended` contains `verify-email`) so the verification can complete after authentication.

- `routes/auth.php` and `routes/web.php`
  - Confirmed verification routes and applied `verified` middleware to `dashboard`.

- `.env`
  - Ensure `APP_URL` is set to the running host and port: e.g. `APP_URL=http://127.0.0.1:8000` if `php artisan serve` listens on port 8000.
  - Ensure `MAIL_MAILER` is a string mailer name (e.g. `smtp`, `log`).

- `app/Providers/AppServiceProvider.php`
  - (Optional) added a listener for `Illuminate\Mail\Events\MessageSent` that writes a copy of each outgoing message to your application log when `MAIL_LOG=true` in `.env`. This allows using SMTP and still keeping a local log copy.

- Temporary command used for testing (created then removed): `app/Console/Commands/SendVerification.php` — creates/reuses a test user and sends verification. Remove it from repo when done.

## Common problems and fixes

1. Error: "Mailer [1] is not defined"
   - Cause: `.env` value was `MAIL_MAILER=true` (boolean), which cast to `1` in config and MailManager attempted to use mailer named `1`.
   - Fix: set `MAIL_MAILER=smtp` (or `log`) in `.env` and run `php artisan config:clear`.

2. Verification URL 404
   - Cause: verification link used `http://localhost/verify-email/...` but your dev server is running on `127.0.0.1:8000` (different host/port). Requests to `localhost` hit a different server (port 80) and return 404.
   - Fix: set `APP_URL=http://127.0.0.1:8000` and run `php artisan config:clear`. Then resend verification so new URL uses correct host/port.
   - Alternative: open the logged URL and change `localhost` to `127.0.0.1:8000` manually in the browser (temporary workaround).

3. Clicking verification link redirects to login and after login you see "You must verify your email address before logging in."
   - Cause: Laravel's verification route requires the user be authenticated. If you block unverified users from logging in too early, the login will be rejected before the framework can redirect back to the original intended verification URL.
   - Fix implemented: allow login to proceed if the intended URL contains `verify-email` (we check `session('url.intended')`). This permits authentication then redirection back to the verification URL so the signature/id/hash checks can run and mark the user as verified.
   - Alternative: don't block login at all (not recommended). Or instruct users to login first, then paste the verification link in the browser.

4. `url.intended` lost between redirect and login
   - Cause: session cookie not persisted or sessions not stored (e.g. SESSION_DRIVER misconfigured, sessions table missing, domain mismatch).
   - Checks:
     - `SESSION_DRIVER` in `.env` (we used `database` in this project).
     - `sessions` table exists: run `php artisan tinker --execute="echo Illuminate\Support\Facades\DB::getSchemaBuilder()->hasTable('sessions') ? 'YES' : 'NO';"`
     - SESSION_DOMAIN in `.env` — leave `null` for local dev or set to appropriate host.

5. I want SMTP delivery and also a log copy
   - Keep `MAIL_MAILER=smtp` in `.env`.
   - Add `MAIL_LOG=true` to `.env`.
   - We added a `MessageSent` listener (in `AppServiceProvider`) that writes outgoing messages to the Laravel log when `MAIL_LOG=true`.
   - Caveat: logged messages may include long bodies/attachments; disable `MAIL_LOG` if you don't want that.

## Useful commands (PowerShell, assuming WSL) and quick scripts

- Clear config cache after changing `.env`:

```pwsh
wsl php /mnt/d/Desktop/UdemyLaravel/UdemyLaravel/project-1/artisan config:clear
```

- Show verification route:

```pwsh
wsl php /mnt/d/Desktop/UdemyLaravel/UdemyLaravel/project-1/artisan route:list --name=verification.verify
```

- Tail the laravel log (where mails are written when `MAIL_MAILER=log` or when `MAIL_LOG=true`):

```pwsh
wsl tail -n 200 /mnt/d/Desktop/UdemyLaravel/UdemyLaravel/project-1/storage/logs/laravel.log
```

- Create (or mark verified) a user using tinker:

```pwsh
wsl php /mnt/d/Desktop/UdemyLaravel/UdemyLaravel/project-1/artisan tinker --execute="App\\Models\\User::create(['name'=>'Test','email'=>'you@example.com','password'=>Illuminate\\Support\\Facades\\Hash::make('password')]);"
```

- Mark a user as verified via tinker:

```pwsh
wsl php /mnt/d/Desktop/UdemyLaravel/UdemyLaravel/project-1/artisan tinker --execute="App\\Models\\User::where('email','you@example.com')->update(['email_verified_at' => now()]);"
```

- Delete the temporary test user:

```pwsh
wsl php /mnt/d/Desktop/UdemyLaravel/UdemyLaravel/project-1/artisan tinker --execute="App\\Models\\User::where('email','test+verify@example.com')->delete();"
```

- Regenerate autoload (if you add/remove commands/classes):

```pwsh
wsl composer dump-autoload -o -d /mnt/d/Desktop/UdemyLaravel/UdemyLaravel/project-1
```

- Check session table exists (if using database sessions):

```pwsh
wsl php /mnt/d/Desktop/UdemyLaravel/UdemyLaravel/project-1/artisan tinker --execute="echo Illuminate\\Support\\Facades\\DB::getSchemaBuilder()->hasTable('sessions') ? 'YES' : 'NO';"
```

## How to test the whole verification flow locally (recommended)

1. Ensure your dev server is running and note the host and port. For `php artisan serve` it will often be `127.0.0.1:8000`.
2. Set `APP_URL` in `.env` to your dev URL including port, e.g. `APP_URL=http://127.0.0.1:8000` and run `php artisan config:clear`.
3. For local testing, temporarily set `MAIL_MAILER=log` (or keep `MAIL_MAILER=smtp` and set `MAIL_LOG=true`).
4. Register a new user from /register.
5. If using `log` mailer: open `storage/logs/laravel.log` and copy the verification URL. If using SMTP + `MAIL_LOG=true`, the same URL will be logged as well.
6. Click the verification URL. If you are redirected to /login, log in with the same user. Because of the login flow changes, you should be redirected back to the verification URL and verified.
7. Confirm `email_verified_at` is set in the database or that the app redirects to the dashboard with `?verified=1`.

## Reverting / safe rollbacks

- To allow old behavior (auto-login after registration): revert the change in `RegisteredUserController::store` and restore `Auth::login($user)`.
- To disable the verification-blocking on login: revert changes in `LoginRequest::authenticate()` (but this allows unverified users to log in and requires `verified` middleware on protected routes to block access).

## Notes & best practices

- Prefer `MAIL_MAILER=log` for local development where you do not want to send real emails.
- If you must use `smtp` locally, use `MAIL_LOG=true` to keep a local copy of outgoing messages for debugging.
- Always `php artisan config:clear` after editing `.env` during development (or restart the server) so changes are picked up.
- Ensure you are testing against the same host/port the app generates URLs for (APP_URL).
- Keep sessions working (cookie domain, same_site, and SESSION_DRIVER) so `url.intended` persists across redirects.

---

If you want, I can:
- Add `MAIL_LOG=true` to your `.env` automatically and re-send a verification to demonstrate the combined SMTP+log approach.
- Add an admin-only endpoint to view recent logged emails for convenience.

---

Migration conflict: `personal_access_tokens`

- Date: 2026-03-05
- Symptom: `php artisan migrate` failed with `SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'personal_access_tokens' already exists` while the migration `2026_03_03_102412_create_personal_access_tokens_table` was still marked as Pending.
- Cause: the physical table already existed in the database but the record for the migration was not present in the `migrations` table.

Actions taken (non-destructive):

1. Confirmed the migration file: `database/migrations/2026_03_03_102412_create_personal_access_tokens_table.php`.
2. Ran `php artisan migrate:status` and verified the migration was Pending while the table existed.
3. Marked the migration as run by inserting a row into the `migrations` table (batch 2):

   INSERT INTO migrations (migration, batch) VALUES ('2026_03_03_102412_create_personal_access_tokens_table', 2);

4. Re-ran `php artisan migrate` → result: "Nothing to migrate." This preserved the existing table and data.

Notes & verification steps:

- Inspect the table schema to ensure it matches the migration:

  SHOW CREATE TABLE personal_access_tokens;

- Check migration status:

  cd /var/www/html/udemy/basic
  php artisan migrate:status

- If you prefer to recreate the table (destructive), drop it and re-run migrations:

  mysql -h 127.0.0.1 -P 3306 -u root -p'<DB_PASSWORD>' -D udemy_basic -e "DROP TABLE IF EXISTS personal_access_tokens;"
  cd /var/www/html/udemy/basic && php artisan migrate

  Warning: this will remove existing data in that table.

Related changes made earlier in this session:

- `app/Http/Requests/Auth/LoginRequest.php` — updated to authenticate using `username` instead of `email`.
- `resources/views/auth/login.blade.php` — already uses `username` input.
- `app/Models/User.php` and `database/migrations/0001_01_01_000000_create_users_table.php` include the `username` column.

If you'd like me to compare the existing table schema to the migration and reconcile any differences (or perform the destructive recreate), tell me which option you prefer and I'll proceed.

Saved to: `email_error.md` in your project root.
