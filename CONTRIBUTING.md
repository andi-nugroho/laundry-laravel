# Contributing to VAULTLAUNDRY

Thank you for your interest in contributing to **VAULTLAUNDRY**. This document explains how to get started, follow project conventions, and open high-quality pull requests.

## Ground Rules

- Be respectful and constructive. Follow [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md).
- Keep pull requests focused. Avoid unrelated refactors in the same PR.
- Do not change database schema or core booking/payment logic unless the issue explicitly requires it.
- Preserve existing routes, authentication flows, and public assets unless the task requires otherwise.

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 20+
- PostgreSQL (local or via Laravel Sail / Docker Compose)

## Local Setup

```bash
git clone https://github.com/andi-nugroho/laundry-laravel.git
cd laundry-laravel
composer install
npm install
cp .env.example .env
php artisan key:generate
```

If you use Sail:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

Otherwise, configure PostgreSQL in `.env`, then:

```bash
php artisan migrate --seed
```

Start the app:

```bash
npm run dev
php artisan serve
```

Dashboard statistics refresh through lightweight polling, so no websocket daemon or queue worker is required for local development.

## Development Workflow

1. **Fork** the repository on GitHub.
2. **Create a branch** from `main`:
   - `feat/booking-filter`
   - `fix/payment-summary`
   - `docs/readme-update`
3. **Make your changes** with clear scope.
4. **Run quality checks** before opening a PR.
5. **Open a pull request** with a descriptive title, summary, and screenshots for UI changes.

## Coding Style

- Follow existing Laravel and Blade conventions in this repository.
- Match VAULTLAUNDRY branding (`VAULTLAUNDRY`, primary color `#FF6626`).
- Prefer Blade components and existing utility classes (`vault-button`, `vault-card`, `vault-section`) over one-off styles.
- Use Laravel Pint for PHP formatting when you touch PHP files:

```bash
./vendor/bin/pint
# or with Sail:
./vendor/bin/sail pint
```

- Keep controllers thin; validation belongs in Form Requests; authorization in Policies/Middleware.
- Do not commit secrets (`.env`, credentials, API keys).

## Quality Checks

Run these before submitting a PR:

```bash
npm run build
php artisan test
```

Optional but recommended:

```bash
./vendor/bin/pint --test
php artisan route:list
```

## Pull Request Checklist

- [ ] Clear title and description
- [ ] Linked issue (if applicable)
- [ ] Screenshots or recordings for UI changes
- [ ] README or docs updated when behavior changes
- [ ] `npm run build` passes
- [ ] `php artisan test` passes
- [ ] No unrelated files or refactors bundled in

## Commit Guidance

Use intent-based commit messages:

- `feat: add booking export for kasir`
- `fix: correct partial payment calculation display`
- `docs: update installation steps for Sail`
- `style: polish landing hero composition`

## Reporting Issues

When opening an issue, include:

- Expected behavior
- Actual behavior
- Steps to reproduce
- Environment (OS, PHP, Node, database)
- Screenshots or logs when useful

## Security

Do not report security vulnerabilities in public issues. See [SECURITY.md](SECURITY.md).

## Questions

For general questions, open a GitHub Discussion or issue in the repository:
https://github.com/andi-nugroho/laundry-laravel
