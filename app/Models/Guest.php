<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $primaryKey = 'guest_id';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'id_card_number',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the reservations for the guest.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'guest_id', 'guest_id');
    }
}
