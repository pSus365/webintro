# Entity Relationship Diagram (ERD)

Based on the current database schema (`docker/db/init.sql`) and application logic (`src/repository`), the application consists of three independent entities.

```mermaid
erDiagram
    USERS {
        int id PK
        varchar name
        varchar surname
        varchar email UK
        varchar password
        text avatar_url
    }

    VEHICLES {
        int id PK
        varchar name
        varchar type
        int mileage
        date next_service_date
        numeric estimated_service_cost
        varchar status "check: w trasie, wolny, serwis"
    }

    DRIVERS {
        int id PK
        varchar first_name
        varchar last_name
        date birth_date
        varchar city
        varchar street
        varchar house_number
        date employment_date
        varchar status "check: wolny, w trasie, L4"
    }

    %% Currently there are no defined Foreign Keys in init.sql
    %% USERS ||--o{ VEHICLES : manages
    %% USERS ||--o{ DRIVERS : manages
```

## Description
*   **USERS**: Stores administrator accounts for logging into the Fleet Manager.
*   **VEHICLES**: Stores the fleet inventory with mileage, service data, and status.
*   **DRIVERS**: Stores employee information including personal details and status.

**Note**: Currently, there are no relational links (Foreign Keys) between these tables enforced in the database.
