# config/

This folder contains application-wide configuration files. Every PHP script that needs a database connection or makes API calls requires one or both of these files.

## Files

### `login_page_config.php`
The main configuration file for the application (despite the name, it is used everywhere — not just the login page).

- Establishes the MySQL database connection using `mysqli` and stores it in `$conn`
- Dynamically defines the `BASE_URL` constant by detecting the project root from `$_SERVER['SCRIPT_NAME']`
- `BASE_URL` is used throughout views and controllers to build correct URLs, asset paths, and redirects regardless of the server's directory structure

Default connection settings:
```
Host:     localhost
User:     root
Password: (empty — default for XAMPP/Laragon)
Database: users_db
```
Update these values to match your local MySQL setup.

### `api_config.php`
Stores the Anthropic API key used for AI-powered features (recipe generation and calorie tips). This file is **not committed to version control** — you must create it locally.

Provide the key as a PHP constant or variable that the model files can reference.

## Usage

From any file in `src/views/`, `src/controllers/`, or `src/models/`:

```php
require_once __DIR__ . '/../../config/login_page_config.php';
require_once __DIR__ . '/../../config/api_config.php';
```

The relative path depth is the same from all three directories since they share the same parent (`src/`).

## Security Note

`api_config.php` contains a secret API key and must be listed in `.gitignore`. Never commit API keys or database passwords to version control.
