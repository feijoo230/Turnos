# Project Overview: Turnos System

A comprehensive system for managing appointments ("turnos") for administrative procedures ("tramites") across various offices ("dependencias"). This project provides both a public frontend for users to request appointments and an administrative panel for operators to manage the flow of attendees.

## Tech Stack
- **Framework:** Laravel 8.x
- **Language:** PHP (compatible with 7.3 and 8.0)
- **Database:** MySQL 5.7
- **Frontend:** Vue 2, AdminLTE 4 (Beta), Bootstrap 5, jQuery, SASS
- **Infrastructure:** Docker & Docker Compose
- **Key Packages:**
  - `spatie/laravel-permission`: Role-based access control (RBAC).
  - `barryvdh/laravel-snappy` / `wkhtmltopdf`: PDF generation for turn confirmations.
  - `laravel/socialite`: Google Social Login integration.
  - `laravel/ui`: Authentication scaffolding.

## Project Structure
The project follows the standard Laravel directory structure within the `app/` directory:
- `app/app/Models/`: Core Eloquent models (e.g., `Turno`, `Tramite`, `Dependencia`, `Cliente`).
- `app/app/Http/Controllers/`: Application logic, split between `frontend` and admin controllers.
- `app/app/Helpers/`: Custom utility functions (e.g., `bandejas_entradas.php`).
- `app/database/`: Migrations, seeders, and factories for database management.
- `app/resources/`: Blade templates, Vue components, and SASS files.
- `app/routes/`: `web.php` for standard routes and `api.php` for API endpoints.
- `docker/`: Configuration files for the Docker environment (Nginx, PHP).

## Building and Running

### 1. Prerequisites
- Docker & Docker Compose
- PHP (>= 7.3)
- Composer
- Node.js & npm

### 2. Initial Setup
1. **Clone and Enter Repository:**
   ```bash
   git clone <repo-url> turnos
   cd turnos
   ```
2. **Install Dependencies:**
   ```bash
   cd app
   composer install
   npm install
   ```
3. **Environment Configuration:**
   ```bash
   cp .env.example .env
   # Update database credentials in .env to match docker-compose.yml (default: MySQL, port 3312)
   ```

### 3. Docker Launch
From the project root:
```bash
docker-compose up -d
```

### 4. Application Initialization
```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app npm run dev
```

### 5. Accessing the Application
- **URL:** [http://localhost:8083](http://localhost:8083)
- **Admin Panel:** [http://localhost:8083/login](http://localhost:8083/login)

## Development Conventions
- **Naming Conventions:** Models use PascalCase (`Turno`), controllers use PascalCase (`TurnosController`), and database tables use snake_case (`turnos`).
- **Assets:** Managed via Laravel Mix. Source files are in `app/resources/js` and `app/resources/sass`.
- **RBAC:** Roles and permissions are managed via `spatie/laravel-permission`. Verify permissions in controllers or middleware.
- **Helpers:** Custom global helpers should be added to `app/app/Helpers` and registered in `app/composer.json` under the `autoload.files` section.
- **PDFs:** Uses Snappy/wkhtmltopdf. Ensure the binary paths are correctly configured in `app/config/snappy.php` if running outside the provided Docker environment.

## Preferencias de Interacción
- **Idioma:** Todas las comunicaciones e interacciones con el asistente deben ser exclusivamente en **español**.
