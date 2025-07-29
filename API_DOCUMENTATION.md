# Laravel API Authentication System

This API provides authentication with user and admin roles using Laravel Sanctum.

## Setup Instructions

1. Run migrations:
```bash
php artisan migrate
```

2. Seed the database with default users:
```bash
php artisan db:seed
```

## Default Accounts

After seeding, you'll have these accounts:
- **Admin**: admin@example.com / password
- **User**: user@example.com / password

## API Endpoints

### Authentication

#### Register
```
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password",
    "role": "user" // optional, defaults to "user"
}
```

#### Login
```
POST /api/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password"
}
```

#### Logout
```
POST /api/logout
Authorization: Bearer {token}
```

#### Get Current User
```
GET /api/user
Authorization: Bearer {token}
```

### User Profile (Authenticated users)

#### Get Profile
```
GET /api/profile
Authorization: Bearer {token}
```

#### Update Profile
```
PUT /api/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Name",
    "email": "newemail@example.com",
    "password": "newpassword",
    "password_confirmation": "newpassword"
}
```

### Admin Only Endpoints

#### Get All Users
```
GET /api/admin/users
Authorization: Bearer {admin_token}
```

#### Get Specific User
```
GET /api/admin/users/{user_id}
Authorization: Bearer {admin_token}
```

#### Update User
```
PUT /api/admin/users/{user_id}
Authorization: Bearer {admin_token}
Content-Type: application/json

{
    "name": "Updated Name",
    "email": "updated@example.com",
    "role": "admin"
}
```

#### Delete User
```
DELETE /api/admin/users/{user_id}
Authorization: Bearer {admin_token}
```

#### Update User Role
```
PUT /api/admin/users/{user_id}/role
Authorization: Bearer {admin_token}
Content-Type: application/json

{
    "role": "admin"
}
```

## Testing the API

You can test the API using tools like Postman, Insomnia, or curl:

### Example: Login and get user info
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Use the token from login response
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Key Features

- ✅ User registration and login
- ✅ Role-based access control (user/admin)
- ✅ JWT token authentication using Laravel Sanctum
- ✅ Profile management
- ✅ Admin user management
- ✅ Secure password hashing
- ✅ Input validation
- ✅ Proper error handling
- ✅ API resource responses

## Role Permissions

### User Role
- Register/Login/Logout
- View and update own profile
- Access user-specific endpoints

### Admin Role
- All user permissions
- View all users
- Create, update, delete users
- Change user roles
- Access admin-only endpoints

## Security Features

- Password confirmation required for registration
- Passwords are hashed using bcrypt
- Admin cannot delete or change role of themselves
- Proper middleware protection for admin routes
- Input validation on all endpoints
