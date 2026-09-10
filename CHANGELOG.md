# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Fixed

- Fix retrieval of dropdown and multiline values containing special characters
- Fix repeated User updates failing with `Data too long for column cookie_token` by excluding token fields from update payloads (backport of PR #566 from GLPI 11-compatible line)
- Fix various minor bugs in the import/export workflow (backport of PR #656 from GLPI 11-compatible line)
- Move network port lookup query to the GLPI DBAL iterator (backport of PR #659 from GLPI 11-compatible line)
- Fix model selector validation and access control (backport of PR #660 from GLPI 11-compatible line)
- Fix validation, permissions and entity handling during imports (backport of PR #664 from GLPI 11-compatible line)
- Improve partial import error reporting (backport of PR #664 from GLPI 11-compatible line)
- Correct user password import and creation handling (policy, history, expiration and confirmation) (backport of PR #664 from GLPI 11-compatible line)

## [2.14.4] - 2025-11-25

### Fixed

- Fix truncated CSV export
- Fix missing `purge` action

## [2.14.3] - 2025-10-14

### Fixed

- Fix `NetworkName` ip adresses injection
- Fix injection to the `date_expiration` of certificates
- Fix the `Task completed` message during plugin update
- Fix `License` injection

## [2.14.2] - 2025-08-22

### Fixed

- Use `global`  configuration for injection links
- Escape  data when check if already exist
- Fix injection of `values in entity tabs` when injecting an `entity`
- Fix `pdffont` field error for users
- Move `Notepads` search options to the Black List option
- Fix the SQL error: `Column ‘...’ cannot be null in the query`


### Added

- Add option to `replace` (instead of `append`)  the value of multiline text fields (e.g. `comment`)


### Removed

- Integration of the WebService plugin (plugin is no longer maintained)


## [2.14.1] - 2024-12-27

### Added

- Add injection of the ```Itemtype```, ```Item``` and ```Path``` for the database instance

### Fixed

- Fix relation (`CommonDBRelation`) insertion
- Fix default entity insertion for a user
- Fixed `SQL` error when creating new injection model
- Fixed issue with missing dropdown options

## [2.14.0] - 2024-10-10

### Added

- Display max upload size
- Add ```default_entity``` to ```User``` injection mapping

### Fixed

- Fix network port unicity
- Fix visibility of injection models
- Fix ```CommonDBRelation``` import
- Fix ```IPAddress``` import which adds a ```lock``` on ```last_update``` field
- Fix ```Agent``` lost when update dynamic asset

## [2.13.5] - 2024-02-22


### Fixed

- Allow lockedfield update
