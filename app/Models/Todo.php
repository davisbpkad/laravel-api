<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'user_id',
        'due_date',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'is_completed',
        'is_overdue',
    ];

    /**
     * Get the user that owns the todo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if todo is completed
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * Mark todo as completed
     */
    public function markAsCompleted(): void
    {
        $this->update(['completed_at' => now()]);
    }

    /**
     * Mark todo as incomplete
     */
    public function markAsIncomplete(): void
    {
        $this->update(['completed_at' => null]);
    }

    /**
     * Check if todo is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }

    /**
     * Get the is_completed attribute for JSON serialization
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->isCompleted();
    }

    /**
     * Get the is_overdue attribute for JSON serialization
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->isOverdue();
    }

    /**
     * Scope for completed todos only
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * Scope for incomplete todos only
     */
    public function scopeIncomplete($query)
    {
        return $query->whereNull('completed_at');
    }

    /**
     * Scope for overdue todos
     */
    public function scopeOverdue($query)
    {
        return $query->whereNull('completed_at')
                    ->whereNotNull('due_date')
                    ->where('due_date', '<', now());
    }
}
