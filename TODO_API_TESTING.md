# Todo API Testing Guide

This guide provides comprehensive examples for testing the Todo API endpoints.

## Setup for Testing

1. **Start the server:**
```bash
php artisan serve
```

2. **Run migrations and seeders:**
```bash
php artisan migrate
php artisan db:seed
```

3. **Login to get token:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

## Todo API Endpoints Testing

### 1. Get All Todos
```bash
# Get all todos (admin sees all, user sees only their own)
curl -X GET http://localhost:8000/api/todos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Filter by status
curl -X GET "http://localhost:8000/api/todos?status=completed" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Admin: Filter by specific user
curl -X GET "http://localhost:8000/api/todos?user_id=2" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN_HERE"
```

### 2. Create Todo
```bash
# Create todo for yourself
curl -X POST http://localhost:8000/api/todos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Learn Laravel Sanctum",
    "deskripsi": "Complete the Laravel Sanctum tutorial and implement authentication",
    "due_date": "2025-08-15"
  }'

# Admin: Create todo for another user
curl -X POST http://localhost:8000/api/todos \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Review code changes",
    "deskripsi": "Review the latest pull requests",
    "due_date": "2025-08-01",
    "user_id": 2
  }'
```

### 3. Get Specific Todo
```bash
curl -X GET http://localhost:8000/api/todos/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4. Update Todo
```bash
curl -X PUT http://localhost:8000/api/todos/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Updated Todo Name",
    "deskripsi": "Updated description with more details",
    "due_date": "2025-08-20"
  }'
```

### 5. Toggle Todo Completion
```bash
# Mark as completed/incomplete
curl -X PATCH http://localhost:8000/api/todos/1/toggle \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 6. Delete Todo
```bash
curl -X DELETE http://localhost:8000/api/todos/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 7. Get Todo Statistics
```bash
# Get your own todo statistics
curl -X GET http://localhost:8000/api/my-todo-stats \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Admin: Get all todo statistics
curl -X GET http://localhost:8000/api/admin/todo-stats \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN_HERE"
```

## Expected Responses

### Todo Object Structure
```json
{
  "id": 1,
  "nama": "Complete project proposal",
  "deskripsi": "Finish writing the project proposal for the new client",
  "due_date": "2025-08-03",
  "completed_at": null,
  "is_completed": false,
  "is_overdue": false,
  "user": {
    "id": 2,
    "name": "Regular User",
    "email": "user@example.com"
  },
  "created_at": "2025-07-29 02:46:12",
  "updated_at": "2025-07-29 02:46:12"
}
```

### Todo Statistics Response
```json
{
  "statistics": {
    "total_todos": 5,
    "completed_todos": 2,
    "incomplete_todos": 3,
    "overdue_todos": 1
  }
}
```

## Permission Testing

### User Permissions
- ✅ Can view only their own todos
- ✅ Can create todos for themselves
- ✅ Can update/delete only their own todos
- ✅ Cannot access admin statistics
- ❌ Cannot view other users' todos
- ❌ Cannot create todos for other users

### Admin Permissions
- ✅ Can view all todos
- ✅ Can create todos for any user
- ✅ Can update/delete any todo
- ✅ Can access admin statistics
- ✅ Can filter todos by user

## Error Cases to Test

### 1. Unauthorized Access
```bash
curl -X GET http://localhost:8000/api/todos
# Expected: 401 Unauthorized
```

### 2. Invalid Todo ID
```bash
curl -X GET http://localhost:8000/api/todos/999 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
# Expected: 404 Not Found
```

### 3. Access Other User's Todo (Non-Admin)
```bash
# Login as regular user, try to access admin's todo
curl -X GET http://localhost:8000/api/todos/1 \
  -H "Authorization: Bearer USER_TOKEN_HERE"
# Expected: 403 Forbidden (if todo belongs to admin)
```

### 4. Invalid Data Validation
```bash
curl -X POST http://localhost:8000/api/todos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "",
    "due_date": "2025-07-01"
  }'
# Expected: 422 Validation Error
```

## Postman Collection Setup

1. Create environment variables:
   - `base_url`: `http://localhost:8000/api`
   - `admin_token`: (set after admin login)
   - `user_token`: (set after user login)

2. Login requests should save tokens:
```javascript
// In Tests tab of login request
pm.environment.set("admin_token", pm.response.json().access_token);
```

3. Use tokens in headers:
```
Authorization: Bearer {{admin_token}}
```
