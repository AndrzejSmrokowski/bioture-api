# AI & Developer Instructions

This project adheres to strict architectural and coding standards. AI assistants and developers must follow these rules without exception.

## 1. Architecture & Design Patterns

### Hexagonal Architecture (Ports & Adapters) / Clean Architecture
-   **Domain Layer** (`src/Domain`): Pure business logic. NO dependencies on infrastructure or framework (Symfony/Doctrine attributes are the only exception allowed for pragmatism, but prefer XML/PHP mapping if strict purity is required. *Current decision: Attributes allowed for simplicity, but kep logic pure*).
-   **Application Layer** (`src/Application`): Use Cases, Command Handlers, Query Handlers. Orchestrates the Domain.
-   **Infrastructure Layer** (`src/Infrastructure`): Implementations of interfaces (Repositories, External APIs), Framework configuration.
-   **User Interface** (`src/ui` or `src/Controller`): Entry points (API Platform Resources, Controllers).

### DDD (Domain-Driven Design)
-   Model the software based on the business domain.
-   Use **Aggregates**, **Entities**, **Value Objects**.
-   **Rich Domain Models**: Entities should contain business logic. No "Anemic Domain Models" (getters/setters only).

### EDA (Event-Driven Architecture)
-   Decouple components using Events.
-   Use **Symfony Messenger** for dispatching and handling events.
-   Async first: Long-running processes should be handled asynchronously via queues.

## 2. Coding Standards (SOLID & Clean Code)

-   **S**ingle Responsibility Principle: Classes should have one reason to change.
-   **O**pen/Closed: Open for extension, closed for modification.
-   **L**iskov Substitution: Subtypes must be substitutable for base types.
-   **I**nterface Segregation: Many client-specific interfaces are better than one general-purpose interface.
-   **D**ependency Inversion: Depend on abstractions, not concretions.

### "Zero Logic in Controllers"
-   Controllers (and API Platform Resources) must be **THIN**.
-   They should only:
    1.  Deserialize input.
    2.  Dispatch a Command/Query (CQRS).
    3.  Return a Response/DTO.
-   **NEVER** put business logic inside a Controller.

## 3. Git Workflow: Conventional Commits

All commit messages must follow the [Conventional Commits](https://www.conventionalcommits.org/) specification.

**Format:**
`type(scope): description`

**Allowed Types:**
-   `feat`: A new feature
-   `fix`: A bug fix
-   `docs`: Documentation only changes
-   `style`: Changes that do not affect the meaning of the code (white-space, formatting, etc)
-   `refactor`: A code change that neither fixes a bug nor adds a feature
-   `perf`: A code change that improves performance
-   `test`: Adding missing tests or correcting existing tests
-   `chore`: Changes to the build process or auxiliary tools and libraries

**Examples:**
-   `feat(cart): add item to shopping cart`
-   `fix(auth): handle expired token exception`
-   `refactor(payment): move gateway logic to infrastructure layer`
-   `docs(readme): add architecture overview`
-   `style(user): apply cs-fixer rules`

## 4. Quality Assurance & CI Strategy

**CRITICAL RULE FOR AI ASSISTANTS:**
Before commiting ANY code, you MUST ensure that it passes all CI checks to avoid breaking the build pipeline.

1.  **Run CS Fixer**: `vendor/bin/php-cs-fixer fix --dry-run --diff --allow-risky=yes`
    -   If errors are found, run without `--dry-run` to fix them automatically, or fix manually.
2.  **Run Static Analysis**: `vendor/bin/phpstan analyse`
    -   All errors must be resolved (Level 8 or max).
3.  **Run Tests**: `vendor/bin/phpunit`
    -   All unit and integration tests must pass.
4.  **Run Rector**: `vendor/bin/rector process --dry-run`
    -   Ensure code is modernized and refactored.
5.  **Run Make CI**: `make ci`
    -   This combines all the above checks + Deptrac. ALWAYS run this before pushing.

**Do not commit broken code.** If a check fails, fix it first.
