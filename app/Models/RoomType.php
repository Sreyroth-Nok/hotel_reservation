<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $primaryKey = 'type_id';
    
    protected $fillable = [
        'type_name',
        'price_per_night',
        'description',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
    ];

    // Relationship: RoomType has many rooms
    public function rooms()
    {
        return $this->hasMany(Room::class, 'type_id', 'type_id');
    }

    // Method: Get available rooms of this type
    public function getAvailableRooms()
    {
        return $this->rooms()->where('status', 'available')->get();
    }

    // Method: Get total rooms count
    public function getTotalRoomsCount(): int
    {
        return $this->rooms()->count();
    }

    // Method: Format price
    public function getFormattedPrice(): string
    {
        return '$' . number_format($this->price_per_night, 2);
    }
}