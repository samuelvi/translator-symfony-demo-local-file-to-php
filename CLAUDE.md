# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This is a lightweight Symfony demo application for the Spreadsheet Translator functionality. It demonstrates how to read a local spreadsheet file (XLSX) and generate translation files per locale in PHP format.

**Key Dependencies:**
- PHP 8.4+
- Symfony 7.3+
- Spreadsheet Translator core library and plugins:
  - `samuelvi/spreadsheet-translator-core` - Core translator functionality
  - `samuelvi/spreadsheet-translator-symfony-bundle` - Symfony integration
  - `samuelvi/spreadsheet-translator-provider-localfile` - Local file provider
  - `samuelvi/spreadsheet-translator-reader-xlsx` - XLSX reader
  - `samuelvi/spreadsheet-translator-exporter-php` - PHP file exporter

## Development Environment

The project uses Docker for development. All commands should be run through the Makefile or directly in the Docker container.

### Docker Commands

```bash
# Start development environment
make up

# Access PHP container shell
make shell

# Stop environment
make down

# Rebuild containers
make build
```

### Running the Demo Application

**Inside Docker container:**
```bash
bin/console atico:demo:translator --sheet-name=common --book-name=frontend
```

**Using Make (from host):**
```bash
make demo  # Runs translator with default options
make console atico:demo:translator --sheet-name=common --book-name=frontend
```

This command reads `config/data/homepage.xlsx` and generates translation files in the `translations/` directory:
- `translations/demo_common.en_GB.php`
- `translations/demo_common.es_ES.php`
- `translations/demo_common.fr_FR.php`

### Dependency Management

```bash
# Install dependencies
make composer-install

# Update dependencies
make composer-update
```

### Code Quality with Rector

Rector is configured with modern syntax (v2.x) to upgrade code to PHP 8.4 and Symfony 7.3 standards.

```bash
# Check changes without applying (dry-run)
make rector-dry-run
bin/rector process --dry-run  # Direct command

# Apply Rector changes
make rector
bin/rector process            # Direct command
```

Rector configuration (`rector.php`) uses fluent configuration style:
- PHP 8.4 upgrade rules (`withPhpSets`)
- Code quality, dead code removal, type declarations (`withPreparedSets`)
- Symfony 7.1+ code quality rules
- Constructor injection patterns
- Doctrine annotations to attributes conversion
- Parallel processing enabled for faster execution
- Cached results in `var/cache/rector`

### Testing

The project includes comprehensive unit tests:

```bash
# Run all tests
make test
bin/phpunit  # Direct command

# Run tests with code coverage
make test-coverage

# Run all quality checks (Rector + tests)
make qa
```

**Test Structure:**
- PHPUnit 11.5+ with modern PHP 8.4 attributes (#[Test], #[CoversClass])
- Tests located in `tests/` directory
- Configuration in `phpunit.xml.dist`
- Coverage reports generated in `var/coverage/`

**Test Files:**
- `tests/Command/TranslatorCommandTest.php` - Tests for the translator command (6 tests)
- `tests/KernelTest.php` - Tests for kernel bootstrapping (3 tests)

**CI/CD:**
- GitHub Actions workflow in `.github/workflows/ci.yml`
- Runs on PHP 8.4
- Executes Rector (dry-run) and PHPUnit tests
- Generates code coverage reports with Codecov integration

## Architecture

### Application Structure

```
src/App/
├── Command/
│   └── TranslatorCommand.php  # Main demo command
└── Kernel.php                  # Symfony kernel using MicroKernelTrait

config/
├── packages/
│   └── atico_spreadsheet_translator.yaml  # Translator configuration
├── services.yaml               # Service definitions
└── data/
    └── homepage.xlsx           # Source spreadsheet

translations/                   # Generated translation files
tests/                          # PHPUnit tests
```

### Core Components

**TranslatorCommand** (`src/App/Command/TranslatorCommand.php:31`)
- Console command: `atico:demo:translator`
- Accepts `--sheet-name` and `--book-name` options
- Uses the `SpreadsheetTranslator` service to process spreadsheets
- Demonstrates translation output by showing a translated fragment

**Spreadsheet Translator Configuration** (`config/packages/atico_spreadsheet_translator.yaml`)
- Provider: `local_file` - reads from local filesystem
- Source: `config/data/homepage.xlsx`
- Exporter format: `php`
- Output prefix: `demo_`
- Destination: `translations/` directory

**Service Wiring** (`config/services.yaml`)
- Uses Symfony autowiring and autoconfiguration
- Aliases `SpreadsheetTranslator` to the bundle's manager service
- Auto-registers all services in `src/App/` (excluding Kernel)

### How Translation Processing Works

1. Command is invoked with sheet name and optional book name
2. `SpreadsheetTranslator` service (from bundle) reads the XLSX file
3. For each locale column in the spreadsheet:
   - Extracts translation keys and values
   - Generates a PHP file named `{prefix}{sheet_name}.{locale}.php`
   - Saves to the configured destination folder
4. Translation files can be used immediately by Symfony's translation component

### Symfony Integration

This project uses modern Symfony practices:
- **MicroKernelTrait** for simplified kernel configuration
- **Symfony Runtime** for application bootstrapping
- **Attribute-based commands** (#[AsCommand])
- **Constructor injection** for dependencies
- **YAML-based configuration** in `config/packages/`

## Development Notes

### Docker Container

The PHP container is named `php-atic-lp` and runs PHP 8.4 with necessary extensions for spreadsheet processing.

### Debugging

For debugging with Xdebug, use the `XDEBUG_SESSION=PHPSTORM` environment variable:

```bash
# Inside Docker
XDEBUG_SESSION=PHPSTORM bin/console atico:demo:translator --sheet-name=common --book-name=frontend

# From host
docker-compose -f docker/docker-compose.yaml exec php-atic-lp sh -c "XDEBUG_SESSION=PHPSTORM php bin/console atico:demo:translator --sheet-name=common --book-name=frontend"
```

### Writing Tests

When writing tests for commands that use the TranslatorInterface:
- The actual implementation includes a `setFallbackLocales()` method not in the interface
- Use `TestTranslatorInterface` (defined in test files) which extends `TranslatorInterface` with this method
- Mock `TestTranslatorInterface` instead of `TranslatorInterface` to avoid mock configuration issues
