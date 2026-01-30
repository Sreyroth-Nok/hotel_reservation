<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'room_id';
    
    protected $fillable = [
        'room_number',
        'type_id',
        'status',
    ];

    // Relationship: Room belongs to RoomType
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'type_id', 'type_id');
    }

    // Relationship: Room has many reservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'room_id', 'room_id');
    }

    // Method: Check if room is available
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    // Method: Check if room is occupied
    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }

    // Method: Check if room is under maintenance
    public function isUnderMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    // Method: Change room status
    public function changeStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
    }

    // Method: Get current reservation
    public function getCurrentReservation()
    {
        return $this->reservations()
            ->where('status', 'checked_in')
            ->first();
    }
}