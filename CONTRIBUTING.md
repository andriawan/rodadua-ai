# Contributing to rodaduaAI

## Setup

See [SETUP.md](./SETUP.md) for local development setup instructions.

## Branch Naming

Use descriptive names with forward slashes:
- `feat/short-description` — New features
- `fix/short-description` — Bug fixes
- `refactor/short-description` — Code refactoring
- `test/short-description` — Test additions/changes
- `docs/short-description` — Documentation
- `devops/short-description` — CI/CD and infrastructure

## Commit Messages

Follow conventional commits:

```
type(scope): description

- feat: new feature
- fix: bug fix
- refactor: code change without feature/fix
- test: adding or updating tests
- docs: documentation
- chore: maintenance, dependencies

Examples:
feat(auth): add token refresh endpoint
fix(motorcycle): handle missing color field
test(api): add comparison endpoint tests
```

## Code Standards

### Backend (PHP/Laravel)

- Follow PSR-12 coding standards
- Run `vendor/bin/pint` before committing
- Run `composer phpstan` for static analysis
- All new methods must have type hints
- Keep controllers thin — delegate to services/repositories
- Use DTOs for request data passing

### Frontend (Vue 3 / TypeScript)

- Run `npm run lint` before committing
- TypeScript strict mode is enabled — avoid `any`
- Components should stay under 300 lines
- Use Pinia stores for shared state
- Use composables for reusable logic

## Testing

- All PRs must include tests
- Backend: write feature tests for endpoints + unit tests for services
- Frontend: write component tests for UI elements
- Run `php artisan test` for backend
- Run `npm run test` for frontend

## PR Process

1. Create a branch from `master`
2. Implement your changes with tests
3. Run all quality checks (PHPStan, Pint, ESLint, tests)
4. Open a PR with a clear description
5. Ensure CI passes
6. Request review
