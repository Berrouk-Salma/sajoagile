# Agile App - Repository Pattern Implementation

This document provides an overview of how the Repository Pattern has been implemented in the Agile App project.

## What is the Repository Pattern?

The Repository Pattern is a design pattern that isolates the data layer from the rest of the app. It provides a clean separation of concerns, abstracting the details of data access away from the business logic.

## Benefits of Repository Pattern

- Centralizes data access logic
- Provides better maintainability and readability
- Enables unit testing by allowing mock implementations
- Reduces code duplication
- Makes it easier to change the data source

## Implementation Details

### Directory Structure

```
app/
├── Repositories/
│   ├── BaseRepository.php
│   ├── Eloquent/
│   │   ├── UserRepository.php
│   │   ├── TeamRepository.php
│   │   ├── ProjectRepository.php
│   │   ├── SprintRepository.php
│   │   ├── TaskRepository.php
│   │   ├── CommentRepository.php
│   │   ├── NotificationRepository.php
│   │   ├── ListaRepository.php
│   │   └── PlanificationRepository.php
│   ├── Interfaces/
│   │   ├── RepositoryInterface.php
│   │   ├── UserRepositoryInterface.php
│   │   ├── TeamRepositoryInterface.php
│   │   ├── ProjectRepositoryInterface.php
│   │   ├── SprintRepositoryInterface.php
│   │   ├── TaskRepositoryInterface.php
│   │   ├── CommentRepositoryInterface.php
│   │   ├── NotificationRepositoryInterface.php
│   │   ├── ListaRepositoryInterface.php
│   │   └── PlanificationRepositoryInterface.php
├── Providers/
│   └── RepositoryServiceProvider.php
```

### Repository Components

1. **RepositoryInterface**: The base interface defining standard CRUD operations.
2. **Entity Interfaces**: Specific interfaces for each entity extending the base interface.
3. **BaseRepository**: The abstract base repository implementing the RepositoryInterface.
4. **Entity Repositories**: Concrete repositories implementing entity-specific interfaces.
5. **RepositoryServiceProvider**: Service provider binding interfaces to implementations.

### How to Use Repositories in Controllers

Repositories are injected into controllers using dependency injection. For example:

```php
class ProjectController extends Controller
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function index()
    {
        $projects = $this->projectRepository->all();
        return response()->json($projects);
    }

    // ...other methods
}
```

### Creating a New Repository

To create a new repository for a new entity:

1. Create an entity-specific interface extending RepositoryInterface
2. Create a concrete repository implementing the entity interface
3. Register the binding in RepositoryServiceProvider

## Database Migrations

The database schema is defined in migration files. Run migrations with:

```bash
php artisan migrate
```

## Seeding the Database

A basic seeder for user roles is provided. Run the seeder with:

```bash
php artisan db:seed
```

This will create:
- Admin user: admin@example.com / password
- Team lead user: lead@example.com / password
- Regular user: user@example.com / password

## Authentication and Authorization

- Authentication is handled using Laravel Sanctum
- Authorization uses a custom CheckRole middleware
- Roles: admin, lead, user

## API Routes

The API is RESTful and follows standard conventions. All routes are protected by authentication except login and register.

## Test the API

You can test the API using tools like Postman or Insomnia:

1. Register a new user: `POST /api/register`
2. Login: `POST /api/login`
3. Use the token in subsequent requests in the Authorization header: `Bearer {token}`

## Adding Features

When adding a new feature that requires data access:

1. Use the appropriate repository
2. Create new methods in the repository interface if needed
3. Implement the methods in the concrete repository
4. Use the repository in your controller via dependency injection

## Conclusion

By implementing the Repository Pattern, the Agile App achieves a clear separation of concerns. The business logic in controllers does not need to know how data is stored or retrieved, making the code more maintainable and testable.
