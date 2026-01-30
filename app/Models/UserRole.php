<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserRole extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationship: User has many reservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id', 'user_id');
    }

    // Method: Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Method: Check if user is staff
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    // Method: Get total reservations count
    public function getTotalReservationsCount(): int
    {
        return $this->reservations()->count();
    }

    // Method: Get active reservations
    public function getActiveReservations()
    {
        return $this->reservations()
            ->whereIn('status', ['booked', 'checked_in'])
            ->get();
    }
}