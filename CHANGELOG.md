# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [unreleased]

### Fixed

- Fix `NetworkName` ip adresses injection

## [2.14.3] - 2025-09-16

### Fixed

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
