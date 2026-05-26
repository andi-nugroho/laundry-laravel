# Security Policy

## Supported Versions

Security fixes are provided for the actively maintained `main` branch of VAULTLAUNDRY.

| Version / Branch | Supported |
| ---------------- | --------- |
| `main` (latest)  | Yes       |
| Older tags       | No        |

If you run a fork or production deployment, keep dependencies updated (`composer update`, `npm update`) and track Laravel security advisories.

## Reporting a Vulnerability

**Please do not open public GitHub issues for security vulnerabilities.**

Report security issues privately using one of these channels:

1. [GitHub Security Advisories](https://github.com/andi-nugroho/laundry-laravel/security/advisories/new) for this repository (preferred)
2. Contact the maintainer through a private channel if you already have one

When reporting, include:

- Description of the vulnerability
- Steps to reproduce
- Affected routes, roles, or components (if known)
- Potential impact (data exposure, privilege escalation, etc.)
- Suggested mitigation or patch (optional)

## Response Targets

- **Initial acknowledgment**: within 72 hours
- **Triage and severity assessment**: as soon as possible
- **Fix timeline**: depends on severity and complexity

## Disclosure

After a fix is available, maintainers may publish a summary describing impact, affected areas, and remediation guidance. We appreciate responsible disclosure and will credit reporters when permitted.

## Scope Notes

Reports related to third-party services (hosting, database provider, payment gateways outside this codebase) should be directed to the respective vendor when appropriate.
