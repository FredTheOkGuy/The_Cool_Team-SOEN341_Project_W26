# project/

This directory contains the full MealMajor application source code. It follows an MVC-inspired structure with clear separation between configuration, public assets, controllers, models, and views.

## Directory Structure

```
project/
├── config/               # Database connection and API configuration
├── public/               # Publicly accessible static assets
│   ├── css/              # Stylesheets (one per page/feature)
│   ├── js/               # Client-side JavaScript
│   └── images/           # Image assets (logo, header, etc.)
├── src/
│   ├── controllers/      # Form handling, input processing, redirects
│   ├── models/           # Database query functions and API wrappers
│   └── views/            # HTML/PHP pages rendered to the browser
└── index.php             # Application entry point (login/register page)
```

## Request Flow

1. The user lands on `index.php` (login/register).
2. Form submissions go to a **controller** (e.g., `src/controllers/login_page_register.php`).
3. The controller calls functions from the **models** (e.g., `src/models/sql_login_register_functions.php`).
4. After processing, the controller redirects to a **view** (e.g., `src/views/main_menu.php`).
5. Views include `config/login_page_config.php` for `$conn` and `BASE_URL`, then query models as needed to render data.

## Requirements

- PHP 8.x
- MySQL (local: Laragon or XAMPP with `users_db` database)
- Composer (for dependency management and running tests)
- Anthropic API key (for AI recipe generation and calorie tips)

## Setup

1. Import the database: use `database/user_db_v5.sql` for local setup, or `database/init.sql` for CI.
2. Configure database credentials in `config/login_page_config.php`.
3. Add your Anthropic API key in `config/api_config.php`.
4. Run `composer install` from the repository root to install dependencies into `vendor/`.
5. Start your local server and navigate to the project in your browser.

## Running Tests

```bash
# From the repository root
composer install
vendor/bin/phpunit
```
