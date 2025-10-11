# Testing Guide

This document explains how to run tests for the Beepi ChatKit Embed plugin.

## Prerequisites

- PHP 8.0 or higher
- Composer (for managing dependencies)

## Installing Dependencies

First, install PHPUnit and other development dependencies:

```bash
composer install
```

This will install PHPUnit and set up the testing environment.

## Running Tests

### Run All Tests

```bash
composer test
```

Or directly with PHPUnit:

```bash
vendor/bin/phpunit
```

### Run Tests with Verbose Output

```bash
vendor/bin/phpunit --verbose
```

## Test Structure

Tests are organized in the `tests/` directory:

- `tests/bootstrap.php` - Test bootstrap file that loads the plugin
- `tests/test-basic.php` - Basic functionality tests

## Writing New Tests

To add new tests:

1. Create a new test file in the `tests/` directory
2. Name it with the `test-` prefix (e.g., `test-shortcode.php`)
3. Extend `PHPUnit\Framework\TestCase`
4. Add your test methods (prefix with `test_`)

Example:

```php
<?php
class Test_MyFeature extends PHPUnit\Framework\TestCase {
    public function test_something() {
        $this->assertTrue(true);
    }
}
```

## Continuous Integration

Tests are automatically run via GitHub Actions on:
- Push to main branch
- Pull requests to main branch

The tests run on multiple PHP versions (8.0, 8.1, 8.2, 8.3) to ensure compatibility.

## Current Test Coverage

The current test suite includes:
- Plugin class existence checks
- Settings class existence checks
- Helper function availability checks
- Activation function checks

Future test additions should cover:
- Shortcode rendering
- Script enqueuing logic
- Settings sanitization
- Admin page rendering

## Troubleshooting

### Composer not found
Install Composer from https://getcomposer.org/

### PHPUnit not found
Run `composer install` to install dependencies

### Tests fail with WordPress errors
The basic tests don't require WordPress. For WordPress integration tests, you would need to set up WordPress test environment separately.
