# Bioture API

## Architecture (DDD + Explicit Persistence)

This project follows a strict Domain-Driven Design (DDD) approach with separation between **Domain** and **Infrastructure**.

### Domain Layer (`src/Exam/Domain/Model`)
*   Contains pure PHP classes representing the valid state of the Exam.
*   **No dependencies on Doctrine** or other infrastructure libraries.
*   Enums are used for type safety (`AnswerFormat`, `TaskType`).

### Infrastructure Layer (`src/Exam/Infrastructure`)
*   **Persistence (`Persistence/Doctrine`)**:
    *   **Entities (`Entity/`)**: Explicit Doctrine Entities (e.g., `ExamEntity`). These map 1:1 to database tables and contain all ORM attributes.
    *   **Mappers (`Mapper/`)**: Classes responsible for converting between Domain Models and Infrastructure Entities. This isolates the Domain from DB schema details.
*   **Factories (`Factory/`)**: Uses `zenstruck/foundry` to create *Infrastructure Entities* for testing/seeding.

### Seeding Data
The fixture `src/Story/BiologyExamStory.php` loads JSON data (e.g., `data/exam/biologia-2025.json`) and persists it using the Infrastructure Factories.
