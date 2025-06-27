<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get user's primary role name
     */
    public function getRoleAttribute()
    {
        return $this->roles->first()?->name ?? 'pengguna';
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Get formatted role label
     */
    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            default => 'Pengguna'
        };
    }

    /**
     * Get role badge class
     */
    public function getRoleBadgeClassAttribute()
    {
        return match($this->role) {
            'admin' => 'badge-danger',
            default => 'badge-secondary'
        };
    }
}