# MealMajor

**Course:** SOEN 341 — Software Process & Practices
**Semester:** Winter 2026

---

## Team Members

| Name | Student ID | Role |
|------|-----------|------|
| Marie Ella Cambay | 40284457 | Full Stack Developer |
| Charles Dahan | 40263717 | Jr. Full Stack Developer |
| Ilyess Diyane | 40316188 | Jr. Full Stack Developer |
| Frederic Gagne | 40278058 | Full Stack Developer |
| Pablo Hernandez | 40246914 | Sr. Full Stack Developer |
| Quentin Malichecq-Laroche | 40285143 | Scrum Master & Full Stack Developer |

---

## Project Description

MealMajor is a PHP/MySQL web application that helps students plan meals, manage recipes, and track calorie intake. It accounts for dietary restrictions, allergies, preparation time, and budget to simplify daily meal planning. The app integrates the Claude (Anthropic) AI API to generate recipe suggestions from user-provided ingredients and deliver personalized calorie tips.

---

## Features

### User Account Management ✅
- Registration and login with session management
- Profile page for managing:
  - Dietary preferences
  - Allergies and food restrictions
  - Daily calorie goal

### Recipe Management ✅
- Create, edit, and delete recipes
- Each recipe includes ingredients, preparation steps, prep/cook time, estimated cost, difficulty level, and dietary tags
- Search recipes by name
- Filter by prep time, cook time, difficulty, cost, and dietary tags
- Sort recipes by name, time, or cost

### Weekly Meal Planner ✅
- View a weekly meal schedule as a grid
- Assign any saved recipe to a day of the week and meal type (breakfast, lunch, dinner, snack)
- Edit or remove scheduled meals
- Prevents duplicate recipes in the same week

### Calorie Tracker ✅
- Track daily calorie intake against a user-defined goal
- Manually add or remove calorie entries
- AI-generated motivational tips via the Claude API

### AI Recipe Generator ✅
- User inputs available ingredients and a meal type
- Claude API generates a complete recipe suggestion
- Generated recipes can be saved directly to the user's recipe list

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.x |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript |
| AI Integration | Anthropic PHP SDK (`anthropic-ai/sdk`) |
| HTTP Client | Symfony HttpClient + nyholm/psr7 |
| CSRF Protection | paragonie/anti-csrf |
| Validation | respect/validation |
| Testing | PHPUnit 9 |
| Local Dev | Laragon or XAMPP |
| CI/CD | GitHub Actions |

---

## Repository Structure

```
/
├── project/                  # Main application source
│   ├── config/               # Database connection and API configuration
│   ├── public/               # Publicly accessible static assets
│   │   ├── css/              # Stylesheets (one per page)
│   │   ├── js/               # JavaScript files
│   │   └── images/           # Image assets
│   ├── src/
│   │   ├── controllers/      # Form handling and business logic
│   │   ├── models/           # Database queries and API wrappers
│   │   └── views/            # HTML/PHP front-end pages
│   └── index.php             # Entry point (login page)
├── database/
│   ├── init.sql              # Schema used by CI (Docker MySQL)
│   └── user_db_v5.sql        # Full database dump for local setup
├── tests/                    # PHPUnit test suite
├── vendor/                   # Composer dependencies
├── docs/                     # Project documentation and sprint meeting minutes
├── composer.json
├── phpunit.xml
└── .github/workflows/        # GitHub Actions CI pipeline
```

---

## Local Setup

### Prerequisites
- PHP 8.x
- MySQL (via Laragon or XAMPP)
- Composer

### Steps

1. **Clone the repository**
   ```bash
   git clone <repo-url>
   cd The_Cool_Team-SOEN341_Project_W26
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Set up the database**
   - Open phpMyAdmin (or your MySQL client)
   - Create a database named `users_db`
   - Import `database/user_db_v5.sql`

4. **Configure the database connection**
   - Open `project/config/login_page_config.php`
   - Update `$host`, `$user`, `$password`, and `$database` to match your local MySQL setup

5. **Configure the Anthropic API key**
   - Open (or create) `project/config/api_config.php`
   - Add your Anthropic API key as a constant

6. **Start your local server**
   - Launch Laragon or XAMPP and point the web root to the project root
   - Navigate to the project URL in your browser

---

## Running Tests

```bash
composer install
vendor/bin/phpunit
```

On Windows (Laragon):
```bash
vendor\bin\phpunit
```

Tests are located in `tests/` and use a sandbox subprocess pattern — no live database connection is required to run them.

---

## CI/CD

GitHub Actions runs on every push and pull request (all branches). The pipeline:
1. Spins up a MySQL 8.0 Docker container and initializes it with `database/init.sql`
2. Validates PHP syntax across all `.php` files
3. Runs `composer install`
4. Executes the PHPUnit test suite

See `.github/workflows/ci.yml` for the full configuration.
