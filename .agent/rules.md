# AI & Developer Project Rules

**CRITICAL PRIORITY:**  
These rules are the **source of truth** for all development.  
Ignore them at your own peril.

---

## 1. Architecture: The "Pure Domain" Law

### **Strict Hexagonal / Clean Architecture**

*   **Domain Layer (`src/**/Domain`)**:
    *   **ABSOLUTELY NO FRAMEWORK DEPENDENCIES.**
    *   **NO Doctrine Attributes**  
        (ORM mapping belongs in Infrastructure via XML / PHP config or separate Infrastructure Entities).
    *   **Pure PHP classes only.**
    *   **Immutable by default** – use `readonly` classes or properties whenever possible.
    *   **Domain is TIME-INDEPENDENT**:
        *   ❌ No `now()`
        *   ❌ No `DateTimeImmutable::now()`
        *   ❌ No system clock access
        *   ✅ Time MUST be injected via constructor or method
    *   **Interfaces (Ports) ONLY** for external concerns:
        *   Repositories
        *   Event Dispatchers
        *   External Services
    *   Domain MAY contain:
        *   Aggregates
        *   Entities
        *   Value Objects
        *   Domain Events
        *   Interfaces (Ports)

*   **Application Layer (`src/**/Application`)**:
    *   Orchestration ONLY.
    *   Use Cases / Command Handlers.
    *   Coordinates Domain objects.
    *   Dispatches Domain Events.
    *   ❌ No persistence
    *   ❌ No framework logic
    *   ❌ No HTTP / CLI logic

*   **Infrastructure Layer (`src/**/Infrastructure`)**:
    *   Implementations of Domain interfaces.
    *   Framework integration (Symfony, Messenger, APIs, DB).
    *   **Persistence Entities**:
        *   Separate from Domain.
        *   ORM mappings **ALLOWED HERE ONLY**.
    *   Uses **ONLY battle-tested Composer libraries**.

*   **UI / Presentation Layer (`src/**/UI`)**:
    *   Controllers, CLI Commands, API Platform Resources.
    *   **ZERO business logic**.
    *   Flow:
        *   Deserialize request
        *   Dispatch command
        *   Return DTO / Response

---

### **Domain-Driven Design (DDD) Deep Dive**

*   **Rich Domain Models**:
    *   Business logic lives inside entities.
    *   `User::activate()` > `UserService::activateUser($user)`

*   **Aggregates**:
    *   Aggregate Root defines transactional boundary.
    *   ONLY Aggregate Root can be loaded/saved via Repository.
    *   No external mutation of internal entities.

*   **Value Objects**:
    *   Used for everything without identity (`Score`, `EmailAddress`, `Money`).
    *   Immutable.
    *   Self-validating in constructor.
    *   No setters.
    *   No nullable state.

*   **Domain Events**:
    *   Represent facts that already happened.
    *   Used ONLY for side effects (emails, logs, integrations).
    *   Domain does NOT know listeners.

---

## 2. Code Quality: Clean Code & SOLID

### **SOLID Principles**

*   **SRP – Single Responsibility**
    *   One class = one reason to change.
    *   If you need the word “and” → split the class.

*   **OCP – Open / Closed**
    *   Extend behavior via new classes, not modifications.

*   **LSP – Liskov Substitution**
    *   Subtypes must be fully replaceable.

*   **ISP – Interface Segregation**
    *   Many small interfaces > one large interface.

*   **DIP – Dependency Inversion**
    *   Domain depends ONLY on abstractions.
    *   Infrastructure depends on Domain, NEVER the opposite.

---

### **Clean Code Rules**

*   **Naming**
    *   Names must express intent.
    *   `calculateTotalScore()` > `calc()`

*   **Functions**
    *   Small.
    *   One responsibility.

*   **Comments**
    *   Explain **WHY**, not **WHAT**.
    *   If explanation is needed → refactor code.

*   **Exceptions**
    *   Exceptional situations ONLY.
    *   NEVER for control flow.

---

## 3. ABSOLUTE RULE: NO CUSTOM ALGORITHMS

**WE DO NOT IMPLEMENT OUR OWN VERSIONS OF:**

*   UUID generation
*   Hashing
*   Cryptography
*   Retry / Backoff mechanisms
*   Random generators
*   Any home-made security logic

**RULES:**
*   Infrastructure MUST use proven Composer libraries.
*   Domain MUST NEVER implement technical algorithms.

---

## 4. Testing Rules (MANDATORY)

### **Test Naming Convention**

**ONLY allowed format:**
```
testShouldReturnXWhenGivenY
```

Example:
```php
public function testShouldThrowExceptionWhenScoreIsNegative(): void
```

---

### **Given / When / Then (REQUIRED)**

EVERY test MUST contain explicit comments:

```php
// Given
$exam = Exam::start(...);

// When
$result = $exam->finish(...);

// Then
self::assertTrue($result->isPassed());
```

Missing Given / When / Then comments = INVALID TEST.

---

## 5. Git Workflow: Conventional Commits

*   **Format**
```
type(scope): description
```

*   **Allowed Types**
    *   feat
    *   fix
    *   refactor
    *   test
    *   docs
    *   style
    *   chore

*   **Rules**
    *   Commit often.
    *   Never mix unrelated changes.
    *   **Task ID in every commit message (MANDATORY):**
        *   Extract Task ID from the branch name prefix: `feature/eng-XXX-...`
        *   Append it at the end of the commit subject in parentheses.
        *   Example branch: `feature/eng-425-add-exam-fixtures`
        *   Example commit: `feat(exam): add fixtures (ENG-425)`
        *   If the branch does not match `feature/eng-XXX-...`, do not invent an ID.

*   **Bot Rule**
    *   Before proposing a commit message:
        *   ALWAYS inspect real diffs (`git diff --staged`).
        *   Message MUST reflect real scope and complexity.
        *   **Ensure the Task ID from the current branch is appended to the commit subject.**


---

## 6. CI/CD & Verification Gatekeeper

**NEVER COMMIT BROKEN CODE.**

Before marking any task as **Done**, run:

* `make quality`

Failure = **NO COMMIT**

---

### **Database & Migrations Rule (MANDATORY)**

*   **Whenever a new Persistence Entity is added or modified in the Infrastructure layer:**
    *   You MUST generate a migration:
        ```bash
        make migration
        ```
    *   You MUST apply the migration locally:
        ```bash
        make migrate
        ```

*   Creating or modifying Infrastructure Entities **without a corresponding migration is FORBIDDEN**.
*   A PR that introduces Infrastructure Entity changes **without updated migrations MUST NOT be approved or merged**.

---

## 7. Responsibility Split (Explicit)

*   **Domain**
    *   Business rules
    *   Aggregates / Entities
    *   Value Objects
    *   Interfaces (Ports)
    *   Domain Events

*   **Infrastructure**
    *   Frameworks
    *   Libraries
    *   Persistence
    *   External integrations
    *   Implementations

---

## 8. Final Rule

> If something feels “simpler” by breaking these rules —  
> **you are doing it wrong.**

---

## ANTIGRAVITY CONFIGURATION

To ensure agents ALWAYS follow these rules, add this to IDE / Agent User Rules:

> **"Always prioritize reading and following rules from `.agent/rules.md`."**
