# Changelog

All notable changes to Streamly CMS are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project follows [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Changed

- Changes currently in development are not yet assigned to a release.

## [2026-09-25]

### Changed

- Updated admin activity-log event filters with an `All` option for viewing all events.
- Updated activity-log filtering to skip event constraints when `All` is selected.
- Updated announcement routes and admin navigation.
- Updated Composer dependencies and lock files.
- Updated Git ignore rules for generated files.

### Added

- Added admin announcement management with listing, filtering, search, pagination, create, edit, delete, and status controls.
- Added announcement create and edit forms with rich-text content and URL validation.
- Added an API controller for retrieving the active announcement.
- Added announcement navigation to the admin sidebar.
- Added category and subcategory API support for support tickets.

### Fixed

- Fixed category list responses for support tickets.

## [2026-09-24]

### Added

- Completed the announcement management feature and related API endpoints.
- Added updated frontend build assets for staging.

## [2026-09-23]

### Added

- Added blog and article search UI.
- Added blog and article API updates.
- Added article category and subcategory browsing improvements.

## [2026-09-21]

### Added

- Completed blog and article page updates.
- Added API support for blog articles and article routes.

## [2026-09-17]

### Added

- Added the initial blog and article page designs.
- Added article category and subcategory components.
- Added blog subcategory API support.

## [2026-09-14]

### Changed

- Updated blog category management with SVG icon support in the admin area.

## [2026-09-12]

### Fixed

- Fixed blog API routes.

## [2026-09-11]

### Changed

- Updated Git ignore rules.
- Merged PHP 8.2 and staging updates.
- Updated custom subscription data handling.
- Updated EPG guide pagination limits.

## [2026-09-10]

### Added

- Added custom subscription export to Excel.

### Changed

- Updated subscription synchronization.
- Updated HOA sub-user session activity handling.

### Fixed

- Fixed custom subscription processing.

## [2026-09-09]

### Changed

- Continued work on the Help and Support feature, including admin category and frontend updates.

## [2026-09-08]

### Added

- Added session-wise activity logging and related admin views.

## [2026-09-07]

### Changed

- Extended JWT token validity from 180 days to 365 days.
- Updated Stripe subscription renewal date handling.
- Continued user session activity tracking improvements.

## [2026-08-31]

### Added

- Added category API and related UI changes.

### Fixed

- Fixed the EPG test API and updated API v2 channel routing.

### Changed

- Added the Help and Support UI design.

## [2026-08-26]

### Added

- Added the admin bug report UI.
- Completed the bug report feature.
- Prepared a new staging build.

## [2026-08-25]

### Added

- Added activity logging.
- Completed the bug report v2 API.

## [2026-08-24]

### Added

- Added API versioning for the EPG guide.

### Fixed

- Fixed API versioning and the EPG v2 controller reference.
