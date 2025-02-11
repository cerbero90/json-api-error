# Changelog

All notable changes to `json-api-error` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com/) principles.


## NEXT - YYYY-MM-DD

### Added
- Nothing

### Changed
- Nothing

### Deprecated
- Nothing

### Removed
- Nothing

### Fixed
- Nothing

### Security
- Nothing


## 0.5.0 - 2025-01-28

### Added
- Fallback route for missing endpoints
- API routes enforce JSON requests by default

### Changed
- When debugging, exception messages are returned in the JSON response before traces
- JsonApiError implements the Illuminate\Contracts\Support\Responsable contract


## 0.4.3 - 2024-05-31

### Fixed
- The HTTP status for Unexpected errors is now 500


## 0.4.2 - 2024-05-27

### Changed
- Rename `shouldHandleRequest()` to `handleRequestIf()`


## 0.4.1 - 2024-05-27

### Fixed
- The `JsonApiError::handle()` parameter docblock


## 0.4.0 - 2024-05-27

### Added
- Allow user-defined logic to determine whether a request should be handled by `JsonApiError`
- `JsonApiSafe` exceptions can customize their own HTTP status code

### Changed
- Rename `JsonApiRenderable` to `JsonApiSafe`


## 0.3.3 - 2024-03-13

### Fixed
- The `JsonApiError::handle()` parameter docblock


## 0.3.1 - 2024-03-12

### Changed
- Rename macro to `assertJsonApiValidation()`


## 0.3.0 - 2024-03-12

### Added
- Middleware to protect API routes


## 0.2.0 - 2024-03-12

### Added
- Automatic handling of JSON:API renderable


## 0.1.0 - 2024-03-12

### Added
- Automatic errors handling
- Registration of custom handlers
- HTTP statuses mapping
- Default data merging
- Localization
- Testing tools
- Trace debugging
