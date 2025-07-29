<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Get todos based on user role
     * - Admin: can see all todos
     * - User: can only see their own todos
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Todo::with('user:id,name,email');

        // Filter by status if provided
        if ($request->has('status')) {
            switch ($request->status) {
                case 'completed':
                    $query->completed();
                    break;
                case 'incomplete':
                    $query->incomplete();
                    break;
                case 'overdue':
                    $query->overdue();
                    break;
            }
        }

        // Filter by user if admin wants to see specific user's todos
        if ($request->has('user_id') && $user->isAdmin()) {
            $query->where('user_id', $request->user_id);
        }

        // Role-based filtering
        if ($user->isAdmin()) {
            // Admin can see all todos
            $todos = $query->latest()->paginate(15);
        } else {
            // User can only see their own todos
            $todos = $query->where('user_id', $user->id)->latest()->paginate(15);
        }

        return response()->json($todos);
    }

    /**
     * Create a new todo
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        $user = $request->user();
        
        // Determine which user the todo belongs to
        if ($request->has('user_id') && $user->isAdmin()) {
            // Admin can create todo for any user
            $userId = $request->user_id;
        } else {
            // Regular user can only create todo for themselves
            $userId = $user->id;
        }

        $todo = Todo::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'user_id' => $userId,
            'due_date' => $request->due_date,
        ]);

        $todo->load('user:id,name,email');

        return response()->json([
            'message' => 'Todo created successfully',
            'todo' => $todo
        ], 201);
    }

    /**
     * Get specific todo
     */
    public function show(Request $request, Todo $todo)
    {
        $user = $request->user();

        // Check if user can access this todo
        if (!$user->isAdmin() && $todo->user_id !== $user->id) {
            return response()->json([
                'message' => 'Access denied. You can only view your own todos.'
            ], 403);
        }

        $todo->load('user:id,name,email');

        return response()->json([
            'todo' => $todo
        ]);
    }

    /**
     * Update todo
     */
    public function update(Request $request, Todo $todo)
    {
        $user = $request->user();

        // Check if user can update this todo
        if (!$user->isAdmin() && $todo->user_id !== $user->id) {
            return response()->json([
                'message' => 'Access denied. You can only update your own todos.'
            ], 403);
        }

        $request->validate([
            'nama' => 'sometimes|string|max:255',
            'deskripsi' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        $data = $request->only(['nama', 'deskripsi', 'due_date']);

        // Only admin can change todo ownership
        if ($request->has('user_id') && $user->isAdmin()) {
            $data['user_id'] = $request->user_id;
        }

        $todo->update($data);
        $todo->load('user:id,name,email');

        return response()->json([
            'message' => 'Todo updated successfully',
            'todo' => $todo
        ]);
    }

    /**
     * Delete todo
     */
    public function destroy(Request $request, Todo $todo)
    {
        $user = $request->user();

        // Check if user can delete this todo
        if (!$user->isAdmin() && $todo->user_id !== $user->id) {
            return response()->json([
                'message' => 'Access denied. You can only delete your own todos.'
            ], 403);
        }

        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully'
        ]);
    }

    /**
     * Toggle todo completion status
     */
    public function toggleComplete(Request $request, Todo $todo)
    {
        $user = $request->user();

        // Check if user can toggle this todo
        if (!$user->isAdmin() && $todo->user_id !== $user->id) {
            return response()->json([
                'message' => 'Access denied. You can only modify your own todos.'
            ], 403);
        }

        if ($todo->isCompleted()) {
            $todo->markAsIncomplete();
            $message = 'Todo marked as incomplete';
        } else {
            $todo->markAsCompleted();
            $message = 'Todo marked as completed';
        }

        $todo->load('user:id,name,email');

        return response()->json([
            'message' => $message,
            'todo' => $todo
        ]);
    }

    /**
     * Get todo statistics (Admin only)
     */
    public function statistics(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'message' => 'Access denied. Admin privileges required.'
            ], 403);
        }

        $stats = [
            'total_todos' => Todo::count(),
            'completed_todos' => Todo::completed()->count(),
            'incomplete_todos' => Todo::incomplete()->count(),
            'overdue_todos' => Todo::overdue()->count(),
            'total_users' => User::count(),
            'users_with_todos' => User::has('todos')->count(),
        ];

        // Todos by user
        $todosByUser = User::withCount(['todos', 'todos as completed_todos_count' => function ($query) {
            $query->completed();
        }])->get(['id', 'name', 'email', 'todos_count', 'completed_todos_count']);

        return response()->json([
            'statistics' => $stats,
            'todos_by_user' => $todosByUser
        ]);
    }

    /**
     * Get user's own todo statistics
     */
    public function myStatistics(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_todos' => $user->todos()->count(),
            'completed_todos' => $user->todos()->completed()->count(),
            'incomplete_todos' => $user->todos()->incomplete()->count(),
            'overdue_todos' => $user->todos()->overdue()->count(),
        ];

        return response()->json([
            'statistics' => $stats
        ]);
    }
}
