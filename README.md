# Laravel API Authentication & Todo System

A robust Laravel API backend with role-based authentication (User & Admin) and comprehensive Todo management system using Laravel Sanctum, designed for SPA (Single Page Application) frontends.

## 🏗️ **Project Structure**

This repository contains the **backend API**. For the complete full-stack application:
- **Backend API** (this repo): `laravel-api/` - Laravel API with authentication and todo management
- **Frontend Client**: `todo-client/` - Vue.js 3 SPA with Pinia state management

## 🚀 Features

- **Role-based Authentication** (User & Admin roles)
- **JWT Token Authentication** using Laravel Sanctum
- **User Registration & Login**
- **Profile Management**
- **Admin User Management**
- **Complete Todo Management System**
  - Create, Read, Update, Delete todos
  - Todo completion tracking with timestamps
  - Due date management with overdue detection
  - Role-based access control (users see only their todos, admins see all)
  - Real-time todo statistics and reporting
  - Role-specific statistics endpoints (`/my-todo-stats` for users, `/admin/todo-stats` for admins)
- **Secure API Endpoints** with proper CORS configuration
- **Input Validation & Error Handling**
- **SQLite Database** (easily switchable to MySQL/PostgreSQL)
- **Production-ready** with comprehensive API documentation

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Laravel 12.x
- SQLite (or MySQL/PostgreSQL)

## 🎯 **Frontend Integration**

This API is designed to work with the **Vue.js todo-client** frontend. The complete application structure:

```
📁 Project Root/
├── 📁 laravel-api/          # Backend API (this repository)
│   ├── app/
│   ├── database/
│   ├── routes/
│   └── ...
└── 📁 todo-client/          # Vue.js 3 Frontend
    ├── src/
    │   ├── components/
    │   ├── stores/
    │   ├── services/
    │   └── views/
    └── ...
```

### **Frontend Features**
- **Vue.js 3** with Composition API
- **Pinia** for state management
- **Real-time stats** without constant API calls
- **Role-based UI** (admin vs user views)
- **Responsive design** with Tailwind CSS
- **Auto-sync** with backend every 5 minutes

## ⚡ Quick Start

### 1. Installation

```bash
# Clone the repository
git clone <your-repo-url>
cd laravel-api

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Database Setup

```bash
# Create SQLite database (if not exists)
touch database/database.sqlite

# Option 1: Fresh setup (recommended for first time)
php artisan migrate:fresh --seed

# Option 2: If tables already exist, just run migrations and seed
php artisan migrate
php artisan db:seed

# Option 3: Use custom command for fresh setup
php artisan db:fresh-seed
```

**Note:** If you get a "UNIQUE constraint failed" error when seeding, it means the users already exist. Use `php artisan migrate:fresh --seed` to start fresh, or the data will be handled by the updated seeders.

### 3. Start the Server

```bash
php artisan serve
```

Your API will be available at: `http://localhost:8000`

## 👥 Default Accounts

After running the seeder, you'll have these test accounts:

| Role  | Email              | Password |
|-------|-------------------|----------|
| Admin | admin@example.com | password |
| User  | user@example.com  | password |

## 📚 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication Headers
For protected routes, include the token in the Authorization header:
```
Authorization: Bearer {your_token_here}
```

---

## 📝 Todo Management Endpoints

### Get All Todos
**GET** `/api/todos`
*Requires Authentication*

Query Parameters:
- `status`: Filter by status (`completed`, `incomplete`, `overdue`)
- `user_id`: Filter by user (Admin only)

**Response:**
```json
{
    "data": [
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
    ],
    "meta": {
        "current_page": 1,
        "total": 1
    }
}
```

### Create Todo
**POST** `/api/todos`
*Requires Authentication*

```json
{
    "nama": "Complete Laravel Tutorial",
    "deskripsi": "Finish the Laravel Sanctum authentication tutorial",
    "due_date": "2025-08-05",
    "user_id": 2
}
```

**Note:** 
- Regular users can only create todos for themselves
- Admins can create todos for any user by specifying `user_id`

### Get Specific Todo
**GET** `/api/todos/{todo_id}`
*Requires Authentication*

### Update Todo
**PUT** `/api/todos/{todo_id}`
*Requires Authentication*

```json
{
    "nama": "Updated todo name",
    "deskripsi": "Updated description",
    "due_date": "2025-08-10"
}
```

### Delete Todo
**DELETE** `/api/todos/{todo_id}`
*Requires Authentication*

### Toggle Todo Completion
**PATCH** `/api/todos/{todo_id}/toggle`
*Requires Authentication*

**Response:**
```json
{
    "message": "Todo marked as completed",
    "todo": {
        "id": 1,
        "nama": "Complete project proposal",
        "completed_at": "2025-07-29 15:30:45",
        "is_completed": true
    }
}
```

### Get My Todo Statistics
**GET** `/api/my-todo-stats`
*Requires Authentication*

**Response:**
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

### Get All Todo Statistics (Admin Only)
**GET** `/api/admin/todo-stats`
*Requires Admin Role*

**Response:**
```json
{
    "statistics": {
        "total_todos": 15,
        "completed_todos": 8,
        "incomplete_todos": 7,
        "overdue_todos": 2,
        "total_users": 5,
        "users_with_todos": 3
    },
    "todos_by_user": [
        {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "todos_count": 3,
            "completed_todos_count": 1
        }
    ]
}
```

---

## 🔐 Authentication Endpoints

### Register User
**POST** `/api/register`

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "user"
}
```

**Response:**
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
    },
    "access_token": "your_token_here",
    "token_type": "Bearer"
}
```

### Login
**POST** `/api/login`

```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "role": "admin"
    },
    "access_token": "your_token_here",
    "token_type": "Bearer"
}
```

### Logout
**POST** `/api/logout`
*Requires Authentication*

**Response:**
```json
{
    "message": "Logged out successfully"
}
```

### Get Current User
**GET** `/api/user`
*Requires Authentication*

**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "role": "admin"
    }
}
```

---

## 👤 User Profile Endpoints

### Get Profile
**GET** `/api/profile`
*Requires Authentication*

### Update Profile
**PUT** `/api/profile`
*Requires Authentication*

```json
{
    "name": "Updated Name",
    "email": "newemail@example.com",
    "password": "newpassword",
    "password_confirmation": "newpassword"
}
```

---

## 🛡️ Admin Only Endpoints

### Get All Users
**GET** `/api/admin/users`
*Requires Admin Role*

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "role": "admin",
            "created_at": "2025-07-29T02:37:54.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 1
    }
}
```

### Get Specific User
**GET** `/api/admin/users/{user_id}`
*Requires Admin Role*

### Update User
**PUT** `/api/admin/users/{user_id}`
*Requires Admin Role*

```json
{
    "name": "Updated Name",
    "email": "updated@example.com",
    "role": "admin",
    "password": "newpassword",
    "password_confirmation": "newpassword"
}
```

### Delete User
**DELETE** `/api/admin/users/{user_id}`
*Requires Admin Role*

### Update User Role
**PUT** `/api/admin/users/{user_id}/role`
*Requires Admin Role*

```json
{
    "role": "admin"
}
```

---

## 🧪 Testing the API

### Using cURL

#### 1. Login and get token
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

#### 2. Use token to access protected routes
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### 3. Admin endpoint example
```bash
curl -X GET http://localhost:8000/api/admin/users \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN_HERE"
```

#### 4. Todo endpoints examples
```bash
# Get all todos
curl -X GET http://localhost:8000/api/todos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Create a new todo
curl -X POST http://localhost:8000/api/todos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"nama":"Learn Laravel","deskripsi":"Complete Laravel tutorial","due_date":"2025-08-05"}'

# Toggle todo completion
curl -X PATCH http://localhost:8000/api/todos/1/toggle \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Get todo statistics
curl -X GET http://localhost:8000/api/my-todo-stats \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Using Postman

1. **Create a new collection** for your API
2. **Set up environment variables:**
   - `base_url`: `http://localhost:8000/api`
   - `token`: (will be set after login)
3. **Login request:**
   - Method: POST
   - URL: `{{base_url}}/login`
   - Body: Raw JSON with email/password
   - In Tests tab, add: `pm.environment.set("token", pm.response.json().access_token);`
4. **For protected routes:**
   - Add Authorization header: `Bearer {{token}}`

---

## 🔧 Configuration

### Database Configuration

By default, the application uses SQLite. To switch to MySQL or PostgreSQL, update your `.env` file:

**For MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api
DB_USERNAME=root
DB_PASSWORD=your_password
```

**For PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_api
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### CORS Configuration

For SPA integration, you may need to configure CORS. Install Laravel Sanctum's SPA authentication:

```bash
# In your .env file, set your SPA URL
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

---

## 🔒 Security Features

### Role-Based Access Control
- **User Role**: Can access profile endpoints and user-specific features
- **Admin Role**: Full access to all endpoints including user management

### Security Safeguards
- Passwords are hashed using bcrypt
- Admin users cannot delete themselves
- Admin users cannot change their own role
- Input validation on all endpoints
- Token-based authentication with Laravel Sanctum

### Middleware Protection
- `auth:sanctum`: Protects authenticated routes
- `admin`: Protects admin-only routes

---

## 🚀 Frontend Integration

This Laravel API is designed to work seamlessly with the **Vue.js todo-client** frontend application.

### **Production Setup**

For production deployment, you'll have:
- **Backend**: Laravel API running on your server (e.g., `https://api.yourdomain.com`)
- **Frontend**: Vue.js SPA served from CDN or static hosting (e.g., `https://app.yourdomain.com`)

### **Quick Start with Vue.js Frontend**

1. **Start the Laravel API** (this repository):
```bash
cd laravel-api
php artisan serve
# API available at http://localhost:8000
```

2. **Start the Vue.js Frontend** (separate repository):
```bash
cd todo-client
npm install
npm run dev
# Frontend available at http://localhost:5173
```

3. **Test the Integration**:
   - Register a new user through the Vue.js frontend
   - Login and start managing todos
   - Create an admin user to see admin features

### **API Endpoints Summary**

The API provides these key endpoints for the frontend:

- **Authentication**: `/api/register`, `/api/login`, `/api/logout`
- **User Management**: `/api/user`, `/api/profile`
- **Todo Management**: `/api/todos` (CRUD operations)
- **Statistics**: `/api/my-todo-stats` (users), `/api/admin/todo-stats` (admins)
- **Admin Features**: `/api/admin/users` (user management)

### **CORS Configuration**

The API is configured to work with the Vue.js frontend. CORS settings in `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:5173', 'http://localhost:3000'],
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

### **Environment Configuration**

Make sure your `.env` file includes:

```env
# For SPA frontend
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:3000
SESSION_DOMAIN=localhost

# CORS settings
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://localhost:3000
```

### Vue.js Frontend Setup

#### 1. Create Vue.js Project

```bash
# Using Vue CLI
npm install -g @vue/cli
vue create my-todo-app
cd my-todo-app

# Or using Vite (recommended)
npm create vue@latest my-todo-app
cd my-todo-app
npm install
```

#### 2. Install Required Dependencies

```bash
# Install Axios for HTTP requests
npm install axios

# Install Vue Router for navigation
npm install vue-router@4

# Install Pinia for state management (optional but recommended)
npm install pinia

# Install additional UI libraries (optional)
npm install @headlessui/vue @heroicons/vue
```

#### 3. Configure API Base URL

Create `src/config/api.js`:
```javascript
// src/config/api.js
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api'

// Create axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// Add request interceptor to include auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Add response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default api
```

#### 4. Create Authentication Service

Create `src/services/authService.js`:
```javascript
// src/services/authService.js
import api from '@/config/api'

export const authService = {
  // Login user
  async login(credentials) {
    try {
      const response = await api.post('/login', credentials)
      const { access_token, user } = response.data
      
      // Store token and user info
      localStorage.setItem('token', access_token)
      localStorage.setItem('user', JSON.stringify(user))
      
      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Register user
  async register(userData) {
    try {
      const response = await api.post('/register', userData)
      const { access_token, user } = response.data
      
      // Store token and user info
      localStorage.setItem('token', access_token)
      localStorage.setItem('user', JSON.stringify(user))
      
      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Logout user
  async logout() {
    try {
      await api.post('/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      // Clear local storage
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  },

  // Get current user
  async getCurrentUser() {
    try {
      const response = await api.get('/user')
      return response.data.user
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Check if user is authenticated
  isAuthenticated() {
    return !!localStorage.getItem('token')
  },

  // Get user from localStorage
  getUser() {
    const user = localStorage.getItem('user')
    return user ? JSON.parse(user) : null
  },

  // Check if user is admin
  isAdmin() {
    const user = this.getUser()
    return user?.role === 'admin'
  }
}
```

#### 5. Create Todo Service

Create `src/services/todoService.js`:
```javascript
// src/services/todoService.js
import api from '@/config/api'

export const todoService = {
  // Get all todos
  async getTodos(filters = {}) {
    try {
      const params = new URLSearchParams()
      Object.keys(filters).forEach(key => {
        if (filters[key]) params.append(key, filters[key])
      })
      
      const response = await api.get(`/todos?${params}`)
      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Get specific todo
  async getTodo(id) {
    try {
      const response = await api.get(`/todos/${id}`)
      return response.data.todo
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Create todo
  async createTodo(todoData) {
    try {
      const response = await api.post('/todos', todoData)
      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Update todo
  async updateTodo(id, todoData) {
    try {
      const response = await api.put(`/todos/${id}`, todoData)
      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Delete todo
  async deleteTodo(id) {
    try {
      const response = await api.delete(`/todos/${id}`)
      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Toggle todo completion
  async toggleTodo(id) {
    try {
      const response = await api.patch(`/todos/${id}/toggle`)
      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Get todo statistics
  async getTodoStats() {
    try {
      const response = await api.get('/my-todo-stats')
      return response.data.statistics
    } catch (error) {
      throw error.response?.data || error.message
    }
  },

  // Admin: Get all todo statistics
  async getAdminTodoStats() {
    try {
      const response = await api.get('/admin/todo-stats')
      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  }
}
```

#### 6. Create Pinia Store (State Management)

Create `src/stores/authStore.js`:
```javascript
// src/stores/authStore.js
import { defineStore } from 'pinia'
import { authService } from '@/services/authService'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: authService.getUser(),
    token: localStorage.getItem('token'),
    loading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin',
    userName: (state) => state.user?.name || 'Guest'
  },

  actions: {
    async login(credentials) {
      this.loading = true
      this.error = null
      
      try {
        const response = await authService.login(credentials)
        this.user = response.user
        this.token = response.access_token
        return response
      } catch (error) {
        this.error = error.message || 'Login failed'
        throw error
      } finally {
        this.loading = false
      }
    },

    async register(userData) {
      this.loading = true
      this.error = null
      
      try {
        const response = await authService.register(userData)
        this.user = response.user
        this.token = response.access_token
        return response
      } catch (error) {
        this.error = error.message || 'Registration failed'
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      this.loading = true
      
      try {
        await authService.logout()
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.user = null
        this.token = null
        this.loading = false
      }
    },

    clearError() {
      this.error = null
    }
  }
})
```

Create `src/stores/todoStore.js`:
```javascript
// src/stores/todoStore.js
import { defineStore } from 'pinia'
import { todoService } from '@/services/todoService'

export const useTodoStore = defineStore('todo', {
  state: () => ({
    todos: [],
    stats: null,
    loading: false,
    error: null,
    filters: {
      status: '',
      user_id: ''
    }
  }),

  getters: {
    completedTodos: (state) => state.todos.filter(todo => todo.is_completed),
    incompleteTodos: (state) => state.todos.filter(todo => !todo.is_completed),
    overdueTodos: (state) => state.todos.filter(todo => todo.is_overdue),
    todosCount: (state) => state.todos.length
  },

  actions: {
    async fetchTodos(filters = {}) {
      this.loading = true
      this.error = null
      
      try {
        const response = await todoService.getTodos({ ...this.filters, ...filters })
        this.todos = response.data
        return response
      } catch (error) {
        this.error = error.message || 'Failed to fetch todos'
        throw error
      } finally {
        this.loading = false
      }
    },

    async createTodo(todoData) {
      this.loading = true
      this.error = null
      
      try {
        const response = await todoService.createTodo(todoData)
        this.todos.unshift(response.todo)
        return response
      } catch (error) {
        this.error = error.message || 'Failed to create todo'
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateTodo(id, todoData) {
      this.loading = true
      this.error = null
      
      try {
        const response = await todoService.updateTodo(id, todoData)
        const index = this.todos.findIndex(todo => todo.id === id)
        if (index !== -1) {
          this.todos[index] = response.todo
        }
        return response
      } catch (error) {
        this.error = error.message || 'Failed to update todo'
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteTodo(id) {
      this.loading = true
      this.error = null
      
      try {
        await todoService.deleteTodo(id)
        this.todos = this.todos.filter(todo => todo.id !== id)
      } catch (error) {
        this.error = error.message || 'Failed to delete todo'
        throw error
      } finally {
        this.loading = false
      }
    },

    async toggleTodo(id) {
      this.loading = true
      this.error = null
      
      try {
        const response = await todoService.toggleTodo(id)
        const index = this.todos.findIndex(todo => todo.id === id)
        if (index !== -1) {
          this.todos[index] = response.todo
        }
        return response
      } catch (error) {
        this.error = error.message || 'Failed to toggle todo'
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchTodoStats() {
      try {
        this.stats = await todoService.getTodoStats()
        return this.stats
      } catch (error) {
        this.error = error.message || 'Failed to fetch statistics'
        throw error
      }
    },

    setFilters(filters) {
      this.filters = { ...this.filters, ...filters }
    },

    clearError() {
      this.error = null
    }
  }
})
```

#### 7. Create Vue Components

Create `src/components/LoginForm.vue`:
```vue
<template>
  <div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    
    <form @submit.prevent="handleLogin">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">
          Email
        </label>
        <input
          v-model="form.email"
          type="email"
          required
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
          placeholder="Enter your email"
        />
      </div>
      
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">
          Password
        </label>
        <input
          v-model="form.password"
          type="password"
          required
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
          placeholder="Enter your password"
        />
      </div>
      
      <div v-if="authStore.error" class="mb-4 text-red-600 text-sm">
        {{ authStore.error }}
      </div>
      
      <button
        type="submit"
        :disabled="authStore.loading"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 disabled:opacity-50"
      >
        {{ authStore.loading ? 'Logging in...' : 'Login' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: ''
})

const handleLogin = async () => {
  try {
    await authStore.login(form)
    router.push('/todos')
  } catch (error) {
    console.error('Login error:', error)
  }
}
</script>
```

Create `src/components/TodoList.vue`:
```vue
<template>
  <div class="max-w-4xl mx-auto mt-8 p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">My Todos</h1>
      <button
        @click="showCreateForm = true"
        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
      >
        Add Todo
      </button>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex gap-4">
      <select
        v-model="filters.status"
        @change="fetchTodos"
        class="px-3 py-2 border rounded-lg"
      >
        <option value="">All Status</option>
        <option value="completed">Completed</option>
        <option value="incomplete">Incomplete</option>
        <option value="overdue">Overdue</option>
      </select>
    </div>

    <!-- Todo Stats -->
    <div v-if="todoStore.stats" class="grid grid-cols-4 gap-4 mb-6">
      <div class="bg-blue-100 p-4 rounded-lg text-center">
        <div class="text-2xl font-bold text-blue-600">{{ todoStore.stats.total_todos }}</div>
        <div class="text-sm text-gray-600">Total</div>
      </div>
      <div class="bg-green-100 p-4 rounded-lg text-center">
        <div class="text-2xl font-bold text-green-600">{{ todoStore.stats.completed_todos }}</div>
        <div class="text-sm text-gray-600">Completed</div>
      </div>
      <div class="bg-yellow-100 p-4 rounded-lg text-center">
        <div class="text-2xl font-bold text-yellow-600">{{ todoStore.stats.incomplete_todos }}</div>
        <div class="text-sm text-gray-600">Incomplete</div>
      </div>
      <div class="bg-red-100 p-4 rounded-lg text-center">
        <div class="text-2xl font-bold text-red-600">{{ todoStore.stats.overdue_todos }}</div>
        <div class="text-sm text-gray-600">Overdue</div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="todoStore.loading" class="text-center py-8">
      <div class="text-gray-600">Loading todos...</div>
    </div>

    <!-- Todo Items -->
    <div v-else class="space-y-4">
      <div
        v-for="todo in todoStore.todos"
        :key="todo.id"
        class="bg-white p-4 rounded-lg shadow border"
        :class="{
          'border-l-4 border-l-green-500': todo.is_completed,
          'border-l-4 border-l-red-500': todo.is_overdue,
          'border-l-4 border-l-blue-500': !todo.is_completed && !todo.is_overdue
        }"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <h3 
              class="text-lg font-semibold"
              :class="{ 'line-through text-gray-500': todo.is_completed }"
            >
              {{ todo.nama }}
            </h3>
            <p class="text-gray-600 mt-1">{{ todo.deskripsi }}</p>
            <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
              <span v-if="todo.due_date">Due: {{ formatDate(todo.due_date) }}</span>
              <span v-if="todo.completed_at">Completed: {{ formatDate(todo.completed_at) }}</span>
              <span class="px-2 py-1 rounded-full text-xs"
                :class="{
                  'bg-green-100 text-green-800': todo.is_completed,
                  'bg-red-100 text-red-800': todo.is_overdue,
                  'bg-blue-100 text-blue-800': !todo.is_completed && !todo.is_overdue
                }"
              >
                {{ todo.is_completed ? 'Completed' : todo.is_overdue ? 'Overdue' : 'Pending' }}
              </span>
            </div>
          </div>
          <div class="flex gap-2 ml-4">
            <button
              @click="toggleTodo(todo.id)"
              class="px-3 py-1 rounded text-sm"
              :class="todo.is_completed 
                ? 'bg-yellow-500 text-white hover:bg-yellow-600' 
                : 'bg-green-500 text-white hover:bg-green-600'"
            >
              {{ todo.is_completed ? 'Undo' : 'Complete' }}
            </button>
            <button
              @click="editTodo(todo)"
              class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600"
            >
              Edit
            </button>
            <button
              @click="deleteTodo(todo.id)"
              class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Todo Modal -->
    <TodoForm
      v-if="showCreateForm || editingTodo"
      :todo="editingTodo"
      @close="closeForm"
      @saved="handleTodoSaved"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useTodoStore } from '@/stores/todoStore'
import TodoForm from './TodoForm.vue'

const todoStore = useTodoStore()
const showCreateForm = ref(false)
const editingTodo = ref(null)

const filters = reactive({
  status: ''
})

const fetchTodos = async () => {
  await todoStore.fetchTodos(filters)
}

const toggleTodo = async (id) => {
  try {
    await todoStore.toggleTodo(id)
  } catch (error) {
    console.error('Toggle error:', error)
  }
}

const editTodo = (todo) => {
  editingTodo.value = todo
}

const deleteTodo = async (id) => {
  if (confirm('Are you sure you want to delete this todo?')) {
    try {
      await todoStore.deleteTodo(id)
    } catch (error) {
      console.error('Delete error:', error)
    }
  }
}

const closeForm = () => {
  showCreateForm.value = false
  editingTodo.value = null
}

const handleTodoSaved = () => {
  closeForm()
  fetchTodos()
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

onMounted(async () => {
  await Promise.all([
    fetchTodos(),
    todoStore.fetchTodoStats()
  ])
})
</script>
```

#### 8. Setup Router

Create `src/router/index.js`:
```javascript
// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import { authService } from '@/services/authService'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/components/LoginForm.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/todos',
    name: 'Todos',
    component: () => import('@/components/TodoList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/',
    redirect: '/todos'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guards
router.beforeEach((to, from, next) => {
  const isAuthenticated = authService.isAuthenticated()
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
  } else if (to.meta.requiresGuest && isAuthenticated) {
    next('/todos')
  } else {
    next()
  }
})

export default router
```

#### 9. Setup Main App

Update `src/main.js`:
```javascript
// src/main.js
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './style.css' // If using Tailwind CSS

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
```

### CORS Configuration for Laravel Backend

To allow your Vue.js frontend to communicate with the Laravel backend, you need to configure CORS.

#### 1. Install Laravel CORS (if not already installed)
```bash
composer require fruitcake/laravel-cors
```

#### 2. Publish CORS config
```bash
php artisan vendor:publish --tag="cors"
```

#### 3. Update `config/cors.php`
```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000', 'http://127.0.0.1:3000', 'http://localhost:5173'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

### Development Workflow

#### 1. Start Laravel Backend
```bash
# In your Laravel project directory
php artisan serve
# Backend runs on http://localhost:8000
```

#### 2. Start Vue.js Frontend
```bash
# In your Vue.js project directory
npm run dev
# Frontend runs on http://localhost:5173 (Vite) or http://localhost:3000 (Vue CLI)
```

#### 3. Test the Integration
1. Open your Vue.js app in the browser
2. Register/Login with the API
3. Create, read, update, delete todos
4. Test role-based features (admin vs user)

### Quick Testing with Browser Console

You can test the API directly from your browser console:

```javascript
// Test login
fetch('http://localhost:8000/api/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    email: 'admin@example.com',
    password: 'password'
  })
})
.then(r => r.json())
.then(data => {
  console.log(data);
  localStorage.setItem('token', data.access_token);
});

// Test getting todos (after login)
fetch('http://localhost:8000/api/todos', {
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'Content-Type': 'application/json'
  }
})
.then(r => r.json())
.then(data => console.log(data));
```

### Common Issues & Troubleshooting

#### 1. CORS Errors
If you see CORS errors in the browser console:
- Make sure Laravel CORS is configured properly
- Check that your frontend URL is in `allowed_origins`
- Restart both backend and frontend servers

#### 2. Authentication Issues
- Verify tokens are being stored and sent correctly
- Check token expiration
- Ensure Sanctum is properly configured

#### 3. API Errors
- Check Laravel logs: `storage/logs/laravel.log`
- Verify database connections
- Test API endpoints with Postman first

#### 4. Network Issues
- Ensure both servers are running
- Check firewall settings
- Verify URLs and ports

### Production Deployment Tips

#### 1. Environment Variables
Update your production `.env` files:

**Laravel `.env`:**
```env
APP_URL=https://your-api-domain.com
SANCTUM_STATEFUL_DOMAINS=your-frontend-domain.com
SESSION_DOMAIN=.your-domain.com
```

**Vue.js `.env.production`:**
```env
VITE_API_BASE_URL=https://your-api-domain.com/api
```

#### 2. Build for Production
```bash
# Build Vue.js app
npm run build

# Deploy Laravel app
# Follow your hosting provider's instructions
```

#### 3. Security Considerations
- Use HTTPS in production
- Set proper CORS origins
- Configure rate limiting
- Use environment-specific configurations
- Set secure session cookies

This setup provides a complete full-stack application with Vue.js frontend consuming your Laravel Todo API backend!

---

## 🛠️ Development

### Adding New Endpoints

1. **Add route** in `routes/api.php`
2. **Create controller** method
3. **Add middleware** if needed
4. **Update this documentation**

### Customizing Roles

To add more roles, update:
1. **Migration**: `database/migrations/*_add_role_to_users_table.php`
2. **User Model**: Add new role check methods
3. **Middleware**: Create new middleware for new roles
4. **Validation**: Update role validation rules

---

## 📝 Error Handling

The API returns consistent error responses:

```json
{
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

Common HTTP status codes:
- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden (insufficient permissions)
- `422`: Validation Error
- `500`: Server Error

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

---

## 📞 Support

If you encounter any issues or have questions:

1. Check the error logs: `storage/logs/laravel.log`
2. Verify your environment configuration
3. Ensure migrations have been run
4. Check that the database is accessible

For development issues, enable debug mode in your `.env` file:
```env
APP_DEBUG=true
```

---

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- Authentication powered by [Laravel Sanctum](https://laravel.com/docs/sanctum)
- Frontend integration examples for [Vue.js](https://vuejs.org)
