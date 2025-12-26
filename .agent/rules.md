# AI & Developer Project Rules

**CRITICAL PRIORITY:** These rules are the source of truth for all development. Ignore them at your own peril.

## 1. Architecture: The "Pure Domain" Law

### **Strict Hexagonal / Clean Architecture**
*   **Domain Layer (`src/**/Domain`)**:
    *   **ABSOLUTELY NO FRAMEWORK DEPENDENCIES.**
    *   **NO Doctrine Attributes** (ORM mapping belongs in Infrastructure via XML or PHP config, or Attributes on separate Infrastructure Entities).
    *   **Pure PHP classes only.**
    *   **Immutable by default**: Use `readonly` classes or properties where possible.
    *   **Interfaces only** for external concerns (Repositories, Event Dispatchers).
*   **Application Layer (`src/**/Application`)**:
    *   Orchestration only.
    *   Use Cases / Command Handlers.
    *   Deeply integrated with Domain, but keeps UI/Infrastructure concerns out.
*   **Infrastructure Layer (`src/**/Infrastructure`)**:
    *   Implementations of Domain Interfaces (e.g., `DoctrineExamRepository`).
    *   Framework integration (Symfony, parsing, external APIs).
    *   **Persistence Entities**: Separate classes with ORM mappings (Attributes allowed HERE only).
*   **UI Layer (`src/**/UI` or Presentation)**:
    *   Controllers, CLI commands, API Platform Resources.
    *   **Zero Logic**: Deserialize -> Dispatch Command -> Return DTO.

### **Domain-Driven Design (DDD) Deep Dive**
*   **Rich Domain Models**: Entities must encapsulate logic. `User::activate()` is better than `UserService->activateUser($user)`.
*   **Aggregates**: Group related objects (e.g., `ExamAttempt` holds `StudentAnswers`).
    *   Only the Aggregate Root can be loaded/saved via Repository.
    *   Transactional boundary.
*   **Value Objects**: Use them for everything that doesn't have identity (e.g., `Score`, `EmailAddress`, `Money`).
    *   They must be immutable and self-validating on construction.
*   **Domain Events**: Side effects (sending emails, logs) MUST be handled via Event Dispatching, not inline code.

## 2. Code Quality: Clean Code & SOLID

### **SOLID Principles (Examples)**
*   **SRP**: A class does ONE thing. If you need "and" to describe it, split it.
    *   *Bad*: `UserManager` (Creates user, sends email, validates password).
    *   *Good*: `UserFactory`, `UserEmailSender`, `PasswordValidator`.
*   **OCP**: Extend behavior by adding classes, not modifying existing ones (Strategy Pattern).
*   **LSP**: A subclass must work wherever its parent works. No specific strict "type checks" breaking inheritance.
*   **ISP**: Client-specific interfaces. `ReaderInterface` vs `WriterInterface` instead of one big `FileInterface`.
*   **DIP**: High-level modules (Domain) should not depend on low-level modules (MySQL). Both should depend on abstractions (Interfaces).

### **Clean Code Rules**
*   **Naming**: Variables/Methods must explain intent. `calculateTotalScore()` > `calc()`.
*   **Functions**: Small. Do one thing.
*   **Comments**: "Why", not "What". Code explains "What". If code is unclear, refactor it, don't comment it.
*   **Exceptions**: Use exceptions for exceptional conditions, not control flow (avoid `try-catch` for logic branching).

## 3. Git Workflow: Conventional Commits
*   **Format**: `type(scope): description`
*   **Types**: `feat`, `fix`, `refactor`, `style`, `test`, `chore`, `docs`.
*   **Rule**: Commit often. Do not mix unrelated changes in one commit.

## 4. CI/CD & Verification "Gatekeeper"
**NEVER COMMIT BROKEN CODE.**
Before you say "Done", run:
1.  `vendor/bin/php-cs-fixer fix` (Style)
2.  `vendor/bin/rector process` (Architecture)
3.  `vendor/bin/phpstan analyse` (Static Analysis - Level Max)
4.  `vendor/bin/deptrac` (Layer Violation Check)
5.  `php bin/console doctrine:schema:validate` (DB Mapping)

---
**ANTIGRAVITY CONFIGURATION:**
To ensure agents (like me) ALWAYS follow this, add this to your IDE "User Rules":
> "Always prioritize reading and following rules from `.agent/rules.md`."
