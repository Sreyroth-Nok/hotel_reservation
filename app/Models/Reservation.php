<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $primaryKey = 'reservation_id';
    
    protected $fillable = [
        'user_id',
        'room_id',
        'check_in',
        'check_out',
        'total_price',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    // Relationship: Reservation belongs to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship: Reservation belongs to Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Relationship: Reservation has many payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'reservation_id', 'reservation_id');
    }

    // Method: Calculate total days (OOP concept from document)
    public function calculateTotalDays(): int
    {
        return Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out));
    }

    // Method: Calculate total price (OOP concept from document)
    public function calculateTotalPrice(float $pricePerNight): void
    {
        $this->total_price = $this->calculateTotalDays() * $pricePerNight;
        $this->save();
    }

    // Method: Check if reservation is active
    public function isActive(): bool
    {
        return in_array($this->status, ['booked', 'checked_in']);
    }

    // Method: Check if reservation is completed
    public function isCompleted(): bool
    {
        return $this->status === 'checked_out';
    }

    // Method: Cancel reservation
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->save();
        
        // Update room status to available
        $this->room->changeStatus('available');
    }

    // Method: Check in
    public function checkIn(): void
    {
        $this->status = 'checked_in';
        $this->save();
        
        // Update room status to occupied
        $this->room->changeStatus('occupied');
    }

    // Method: Check out
    public function checkOut(): void
    {
        $this->status = 'checked_out';
        $this->save();
        
        // Update room status to available
        $this->room->changeStatus('available');
    }

    // Method: Get total paid amount
    public function getTotalPaidAmount(): float
    {
        return $this->payments()->sum('amount');
    }

    // Method: Get remaining balance
    public function getRemainingBalance(): float
    {
        return $this->total_price - $this->getTotalPaidAmount();
    }

    // Method: Check if fully paid
    public function isFullyPaid(): bool
    {
        return $this->getRemainingBalance() <= 0;
    }
}