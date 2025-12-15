# Bioture API

![CI](https://github.com/AndrzejSmrokowski/bioture-api/actions/workflows/ci.yaml/badge.svg)

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

## 📏 Architecture & Standards

This project follows strict guidelines: **DDD**, **Hexagonal Architecture**, **EDA**, and **Zero Logic in Controllers**.
See [AI_INSTRUCTIONS.md](AI_INSTRUCTIONS.md) for detailed rules on coding standards and Conventional Commits.

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
| `make init` | Initialize the project (build, up, install). |
| `make up` | Start the containers in background. |
| `make down` | Stop the containers. |
| `make shell` | Enter the PHP container shell. |
| `make test` | Run PHPUnit tests. |
| `make cs-fix` | Fix coding standards automatically. |
| `make analyze` | Run PHPStan static analysis. |
| `make rector` | Run Rector to refactor code. |
| `make deptrac` | Analyze architectural dependencies. |
| `make ci` | Run full CI suite (cs-fix, rector, analyze, deptrac, test). |

## 📖 API Documentation

Once the server is running, you can access the API Platform documentation (Swagger/OpenAPI):

-   **URL**: `http://localhost:8080/api/docs` (or check your `docker-compose` port mapping).

## 🏗 Project Structure

Reflecting our **DDD** approach, the code is organized by **Bounded Contexts** (Domains):

-   `src/{Domain}/Domain/Model`: Entities, Enums, and Value Objects (The Core).
-   `src/{Domain}/Domain/Service`: Domain Services containing business logic.
-   `src/{Domain}/Infrastructure`: Implementation details (API Platform Resources, State Processors, Repositories).
-   `src/Shared`: Common components shared across domains.
-   `.docker/`: Docker configuration files.
-   `tests/`: PHPUnit tests and Factories.

---
*Built with ❤️ by Antigravity*
