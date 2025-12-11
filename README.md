# Bioture API

![Bioture](https://via.placeholder.com/800x200?text=Bioture+API+Project)

Welcome to **Bioture API** - a bleeding-edge API project built with **Symfony 8.0** and **PHP 8.5**.

## 🚀 Tech Stack

This project is configured to use the latest and greatest technologies available in the PHP ecosystem:

-   **Framework**: [Symfony 8.0](https://symfony.com) (Bleeding Edge)
-   **Language**: **PHP 8.5** (Dev/Nightly via Docker)
-   **API Engine**: [API Platform 4](https://api-platform.com)
-   **Database**: PostgreSQL
-   **ORM**: Doctrine ORM 3
-   **Server**: Nginx + PHP-FPM (Debian-based for stability)

## 🛠 Quality Assurance & Tools

We believe in "Hardcore Quality Assurance". The project comes pre-configured with:

-   **PHPStan** (Level 8): Static Analysis for type safety.
-   **Rector**: Automated refactoring to keep code modern (PHP 8.5 styles).
-   **PHP-CS-Fixer**: Enforces coding standards (PSR-12/Symfony).
-   **Zenstruck Foundry**: Powerful fixtures and factories for testing.
-   **Deptrac**: Architectural dependency enforcement.

> **Note**: *Infection (Mutation Testing)* is temporarily disabled due to compatibility issues with Symfony 8.0 console. Stay tuned!

## 🐳 Docker Setup

The project runs entirely in Docker to ensure environment consistency.

### Prerequisites

-   Docker & Docker Compose
-   Make (optional, but recommended)

### getting Started

Initialize the project with a single command:

```bash
make init
```

This will:
1.  Build the Docker images (PHP 8.5).
2.  Start the containers.
3.  Install Composer dependencies.

## 💻 Development Commands

We provide a `Makefile` to simplify daily tasks:

| Command | Description |
| :--- | :--- |
| `make up` | Start the containers in background. |
| `make down` | Stop the containers. |
| `make shell` | Enter the PHP container shell. |
| `make test` | Run PHPUnit tests. |
| `make cs-fix` | Fix coding standards automatically. |
| `make analyze` | Run PHPStan static analysis. |
| `make rector` | Run Rector to refactor code. |
| `make deptrac` | Analyze architectural dependencies. |

## 📖 API Documentation

Once the server is running, you can access the API Platform documentation (Swagger/OpenAPI):

-   **URL**: `http://localhost:8080/api/docs` (or check your `docker-compose` port mapping).

### Example Resources
-   **Experiment**: A sample entity with validation and logic.
    -   `POST /api/experiments`: Create a new experiment.
    -   `GET /api/experiments`: List all experiments.


## 🏗 Project Structure

-   `src/Entity`: Doctrine Entities (API Resources).
-   `src/Controller`: Custom controllers (if needed outside API Platform).
-   `docker/`: Docker configuration files.
-   `tests/`: PHPUnit tests and Factories.

## 💡 Best Practices

1.  **Make it strict**: Do not ignore PHPStan errors. Fix them.
2.  **Make it clean**: Run `make cs-fix` before every commit.
3.  **Make it simple**: Use API Platform resources for CRUD operations. Avoid custom controllers unless necessary.

---
*Built with ❤️ by Antigravity*
