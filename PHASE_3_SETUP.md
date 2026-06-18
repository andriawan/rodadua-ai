# Phase 3: Backend Architecture & Data Models - COMPLETED

## Overview
Successfully implemented complete backend architecture with 5 core data models, repositories for complex queries, services for business logic, form validation, authorization policies, and full CRUD API endpoints for motorcycles.

## Database Schema

### 5 Models Created

#### 1. Motorcycle Model
```sql
motorcycles
├── id (PK)
├── user_id (FK) - cascade on delete
├── brand, model, year
├── color, license_plate (unique)
├── engine_cc, engine_type, transmission, fuel_type
├── purchase_date, odometer_km
├── notes, status (active/inactive/for_sale)
├── is_favorite (boolean)
├── timestamps, soft_deletes
└── Indexes: brand+model, user_id+status, year
```

#### 2. Maintenance Model
```sql
maintenances
├── id (PK)
├── motorcycle_id (FK) - cascade on delete
├── user_id (FK) - cascade on delete
├── type, description, odometer_km, maintenance_date
├── cost, workshop, status (scheduled/completed/pending)
├── next_maintenance_km, next_maintenance_date
├── notes
├── timestamps, soft_deletes
└── Indexes: motorcycle_id+maintenance_date, user_id+status
```

#### 3. Workshop Model
```sql
workshops
├── id (PK)
├── name, description, phone, email, website
├── address, city, province, postal_code
├── latitude, longitude (for geolocation)
├── rating, total_reviews, specialist_motorcycle_count
├── operating_hours, is_open_weekends
├── services_offered (JSON)
├── timestamps, soft_deletes
└── Indexes: city, rating, latitude+longitude
```

#### 4. SparePart Model
```sql
spare_parts
├── id (PK)
├── name, description, part_number (unique), oem_number
├── category, subcategory, brand
├── compatible_motorcycles (JSON), compatible_years (JSON)
├── retail_price, wholesale_price
├── quantity_available, in_stock (boolean)
├── supplier_name, supplier_code, notes, view_count
├── timestamps, soft_deletes
└── Indexes: category, part_number, in_stock
```

#### 5. TroubleshootingHistory Model
```sql
troubleshooting_histories
├── id (PK)
├── motorcycle_id (FK) - cascade on delete
├── user_id (FK) - cascade on delete
├── problem_description, symptom
├── ai_analysis, suggested_solutions (JSON)
├── severity (low/medium/high/critical)
├── status (open/resolved/in_progress)
├── resolution_notes, resolved_date
├── workshop_feedback, ai_provider, prompt_used
├── user_rating (1-5), user_feedback
├── timestamps, soft_deletes
└── Indexes: motorcycle_id+created_at, user_id+status, severity
```

## Backend Architecture

### 1. DTOs (Data Transfer Objects)
- **CreateMotorcycleDTO** - Validates and transfers motorcycle creation data
- **UpdateMotorcycleDTO** - Handles partial updates with null-coalescing
- **LoginDTO** & **CreateUserDTO** - Auth DTOs (from Phase 2)

Features:
- `fromRequest()` factory method for creating from HTTP requests
- `toArray()` for converting to database-ready arrays
- Type safety with strict property declarations

### 2. Repositories (Query Layer)
Implement complex query logic without N+1 problems:

#### MotorcycleRepository
```php
getAllByUser($userId, $filters)      // With eager loading
getByIdWithRelations($id)            // Load all relationships
getFavorites($userId)
getByBrand($brand)
getRecent($userId)
create(), update(), delete()
```

#### MaintenanceRepository
```php
getByMotorcycleId($motorcycleId, $filters)
getUpcoming($motorcycleId)           // Next scheduled maintenance
getByUserId($userId)                 // All user's maintenance history
create(), update(), delete()
```

#### WorkshopRepository
```php
getAll($filters)                     // With search, city, rating filters
getNearby($lat, $lng, $radius)       // Geolocation-based search
getHighRated()
getByCity($city)
create(), update(), updateRating()
```

**Key Features:**
- Eager loading relationships with `with()` to prevent N+1 queries
- Complex queries encapsulated in repository methods
- Filters applied via query chains
- Pagination support with `paginate()`
- All return types clearly defined

### 3. Services (Business Logic)
Keep controllers thin by delegating to services:

#### MotorcycleService
```php
getAllByUser($userId, $filters)
getById($motorcycleId)
create($userId, CreateMotorcycleDTO)
update(Motorcycle, UpdateMotorcycleDTO)
delete(Motorcycle)
toggleFavorite(Motorcycle)
updateOdometer(Motorcycle, $km)
getByBrand($brand)
getRecent($userId)
```

**Features:**
- Uses repository for database operations
- DTOs for type-safe data handling
- Error handling and exception management
- Business rule enforcement

### 4. Form Requests (Validation)
All input validated before reaching business logic:

#### StoreMotorcycleRequest
- brand: required, string, max 255
- model: required, string, max 255
- year: required, integer, 1900 to current year
- license_plate: unique, nullable
- engine_cc, engine_type, transmission, fuel_type: nullable with constraints
- odometer_km: nullable, integer, min 0
- notes: nullable, max 1000

#### UpdateMotorcycleRequest
- All fields nullable for partial updates
- Validates license_plate uniqueness (excluding current record)
- Authorizes that user owns the motorcycle

### 5. Controllers (API Endpoints)
Thin controllers that delegate to services:

#### MotorcycleController
```
GET    /api/v1/motorcycles               → index()
GET    /api/v1/motorcycles/{id}          → show()
POST   /api/v1/motorcycles               → store()
PUT    /api/v1/motorcycles/{id}          → update()
DELETE /api/v1/motorcycles/{id}          → destroy()
POST   /api/v1/motorcycles/{id}/toggle-favorite  → toggleFavorite()
PUT    /api/v1/motorcycles/{id}/odometer        → updateOdometer()
```

**Controller Pattern:**
- Inject service via constructor
- Use form requests for validation
- Call single service method
- Return standardized JSON response
- Handle exceptions gracefully

### 6. Resources (Response Formatting)
Consistent API responses using Laravel Resources:

#### MotorcycleResource
```json
{
  "id": 1,
  "user_id": 1,
  "brand": "Honda",
  "model": "CB150R",
  "year": 2022,
  "odometer_km": 5000,
  "status": "active",
  "is_favorite": true,
  "maintenances_count": 2,
  "troubleshooting_count": 0,
  "created_at": "2026-06-18T23:01:00Z"
}
```

### 7. Policies (Authorization)
Fine-grained access control:

#### MotorcyclePolicy
- `view()` - User can only view own motorcycles
- `create()` - All authenticated users can create
- `update()` - Only owner can update
- `delete()` - Only owner can delete

Automatically checked in controllers:
```php
$this->authorize('view', $motorcycle);
```

Returns 403 if unauthorized.

## Key Architecture Decisions

### 1. Eager Loading Strategy
Prevent N+1 queries:
```php
Motorcycle::with(['maintenances', 'troubleshootingHistories'])->get()
```

### 2. Soft Deletes
All models use soft deletes for data recovery:
```php
Schema::enableForeignKeyConstraints();
$table->softDeletes();
```

### 3. Filtering Pattern
Flexible filtering via query builders:
```php
$query->where('brand', $brand)
      ->where('status', $status)
      ->paginate()
```

### 4. DTO Pattern
Type-safe data transfer:
```php
new CreateMotorcycleDTO('Honda', 'CB150R', 2022, ...)
```

### 5. Repository Pattern
Encapsulate all database queries:
```php
$repository->getAllByUser($userId, $filters)
```

## API Response Format

### Success Response (200 OK)
```json
{
  "success": true,
  "data": { "motorcycle": {...} },
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 42,
    "last_page": 3
  }
}
```

### Error Response (400/401/403/500)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "brand": ["The brand field is required"],
    "model": ["The model field is required"]
  }
}
```

## Files Created/Modified

### Migrations (5 new)
- `2026_06_18_160106_create_motorcycles_table.php`
- `2026_06_18_160126_create_maintenances_table.php`
- `2026_06_18_160127_create_workshops_table.php`
- `2026_06_18_160129_create_spare_parts_table.php`
- `2026_06_18_160131_create_troubleshooting_histories_table.php`

### Models (5 new)
- `app/Models/Motorcycle.php` - With relationships and scopes
- `app/Models/Maintenance.php`
- `app/Models/Workshop.php`
- `app/Models/SparePart.php`
- `app/Models/TroubleshootingHistory.php`

### DTOs (2 new)
- `app/DTOs/CreateMotorcycleDTO.php`
- `app/DTOs/UpdateMotorcycleDTO.php`

### Repositories (3 new)
- `app/Repositories/MotorcycleRepository.php`
- `app/Repositories/MaintenanceRepository.php`
- `app/Repositories/WorkshopRepository.php`

### Services (1 new)
- `app/Services/MotorcycleService.php`

### Controllers (1 new)
- `app/Http/Controllers/Api/MotorcycleController.php`

### Form Requests (2 new)
- `app/Http/Requests/StoreMotorcycleRequest.php`
- `app/Http/Requests/UpdateMotorcycleRequest.php`

### Resources (2)
- `app/Http/Resources/MotorcycleResource.php` (new)
- `app/Http/Resources/UserResource.php` (existing)

### Policies (1 new)
- `app/Policies/MotorcyclePolicy.php`

### Routes
- Updated `routes/api.php` with motorcycle endpoints

## Architecture Diagram

```
HTTP Request
    ↓
Route (api.php)
    ↓
Controller (thin)
  ├─ FormRequest (validation)
  └─ Service (business logic)
      └─ Repository (queries with eager loading)
          └─ Model (relationships)
              └─ Database
    ↓
Resource (format response)
    ↓
JSON Response
```

## Follows All AGENTS.md Rules ✅

- [x] Controllers remain thin (delegate to services)
- [x] Business logic in Services
- [x] Complex queries in Repositories
- [x] Validation in Form Requests
- [x] Add indexes where appropriate
- [x] Use foreign keys with cascade rules
- [x] Eager loading with `with()`
- [x] Never use N+1 queries
- [x] API response format is consistent
- [x] Authorization via Policies/Gates
- [x] TypeScript (frontend) strict mode compliance
- [x] No hardcoded database logic in controllers

## Testing Endpoints

### Create Motorcycle
```bash
curl -X POST http://localhost:8000/api/v1/motorcycles \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "brand": "Honda",
    "model": "CB150R",
    "year": 2022,
    "engine_cc": 150,
    "transmission": "manual"
  }'
```

### Get User Motorcycles
```bash
curl -X GET "http://localhost:8000/api/v1/motorcycles?status=active" \
  -H "Authorization: Bearer TOKEN"
```

### Update Motorcycle
```bash
curl -X PUT http://localhost:8000/api/v1/motorcycles/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"odometer_km": 5500}'
```

### Toggle Favorite
```bash
curl -X POST http://localhost:8000/api/v1/motorcycles/1/toggle-favorite \
  -H "Authorization: Bearer TOKEN"
```

## Performance Considerations

1. **Indexes on**:
   - Foreign keys (motorcycle_id, user_id)
   - Search fields (brand, model, license_plate)
   - Filter fields (status, year, city)
   - Geolocation (latitude, longitude)

2. **Eager Loading**:
   - Load relationships in one query
   - Prevents N+1 query problems
   - Paginated results reduce memory usage

3. **Soft Deletes**:
   - `whereNotNull('deleted_at')` scope applied automatically
   - `onlyTrashed()` for archived items

## Next Phase (Phase 4): Frontend Architecture

Will implement:
- Vue 3 components for motorcycle list
- Motorcycle detail page
- Form components for create/edit
- Integration with API
- Route guards for protected pages
- State management in Pinia

---

**Status**: Phase 3 Complete ✅
**Date**: 2026-06-18
**Next**: Proceed to Phase 4 - Frontend Architecture & Components
