<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'string',
            'created_at' => 'datetime',
        ];
    }

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
