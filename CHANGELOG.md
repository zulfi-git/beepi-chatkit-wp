# Changelog

All notable changes to the Beepi ChatKit Embed plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed
- Corrected ChatKit custom element name from 'chatkit-widget' to 'openai-chatkit'
- Resolved ChatKit SDK initialization failure caused by waiting for incorrect custom element name

## [1.5.0] - 2025-10-12

### Fixed
- Fixed ChatKit SDK initialization by implementing Web Component (custom element) approach
- Replaced window.ChatKit polling with customElements.whenDefined() for proper Web Component support
- Updated initialization to create and configure openai-chatkit custom element
- Added fallback polling mechanism for older browsers
- Resolved "ChatKit SDK not loaded" error by using correct integration pattern

## [1.4.1] - 2025-10-12

### Changed
- Implemented `filemtime()` cache busting for all JavaScript and CSS assets
- Replaced hardcoded version strings with file modification timestamps
- Ensures browsers fetch updated assets when files change

## [1.4.0] - 2025-10-12

### Added
- Worker health status display in admin settings page
- Real-time health monitoring with AJAX refresh
- Health status endpoint support (`/api/health`)
- Visual indicators for worker status (OK/Error)
- Version information display
- Refresh button for manual health checks
- New admin JavaScript (`admin-health.js`) for AJAX functionality
- New admin CSS (`admin-health.css`) for health status styling
- AJAX handler with security nonce validation
- Health check function using WordPress HTTP API

### Changed
- Removed uptime field from health endpoint response (not meaningful in stateless serverless context)
- Updated health status display to show only status and version
- Removed `formatUptime()` function from admin JavaScript

### Security
- AJAX requests protected with WordPress nonce verification
- Capability checks for admin-only health status access

## [1.3.0] - 2025-10-11

### Added
- Created `/docs` directory for better documentation organization

### Changed
- Moved assessment documents to `/docs` folder (ASSESSMENT.md, ASSESSMENT-SUMMARY.md, IMPLEMENTATION-GUIDE.md)
- Updated documentation references to reflect new structure
- Consolidated and removed redundant REVIEW-CHECKLIST.md
- Streamlined documentation for one-person project (removed fluff)

### Improved
- Documentation organization and discoverability
- Reduced documentation redundancy

## [1.2.0] - 2025-10-11

### Added
- WordPress admin settings page for easy configuration
- Settings link on plugins page for quick access
- Database-backed configuration (no more file editing)
- Activation hook to set default options
- Uninstall hook for proper cleanup
- PHPUnit test infrastructure
- Basic PHP unit tests
- GitHub Actions workflow for automated testing
- Dependabot configuration for dependency management
- Testing documentation (TESTING.md)
- Dedicated CHANGELOG.md file
- Composer configuration for dependency management

### Changed
- Configuration now uses WordPress options API instead of constants
- Updated documentation to reflect new admin UI
- Removed emojis from README and changelog
- Streamlined changelog format
- Version bumped to 1.2.0 across all files
- Updated assessment files to mark completed phases

### Improved
- Input sanitization and validation for settings
- Security with proper capability checks
- User experience with admin interface
- Maintainability with test infrastructure

## [1.1.0] - 2025-10

### Added
- Comprehensive codebase assessment (ASSESSMENT.md)
- Quick assessment summary (ASSESSMENT-SUMMARY.md)
- Implementation guide (IMPLEMENTATION-GUIDE.md)
- Contributing guidelines (CONTRIBUTING.md)
- Review checklist (REVIEW-CHECKLIST.md)
- Development configuration files (.editorconfig, .gitattributes, .gitignore)
- Enhanced troubleshooting section in README
- JSDoc comments in JavaScript

### Changed
- Updated to follow official OpenAI ChatKit embedding guide
- Changed from tokenProvider pattern to api.getClientSecret pattern
- Updated API integration to use client_secret instead of token

### Improved
- Documentation quality
- Code comments
- Compatibility with OpenAI's recommended implementation

## [1.0.0] - Initial Release

### Added
- Initial plugin release
- Shortcode support for embedding ChatKit (`[chatkit]`)
- Cloudflare Worker integration for token management
- Conditional script loading (performance optimization)
- Responsive design with basic styling
- OpenAI ChatKit library integration
- Custom CSS support

### Features
- Easy integration via shortcode
- Secure token management
- Mobile-responsive design
- WordPress standards compliance
- Proprietary licensing
