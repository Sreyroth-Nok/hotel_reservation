<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
// 
    protected $primaryKey = 'payment_id';
    
    protected $fillable = [
        'reservation_id',
        'amount',
        'payment_method',
        'payment_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    // Relationship: Payment belongs to Reservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }

    // Method: Format amount
    public function getFormattedAmount(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    // Method: Get payment method label
    public function getPaymentMethodLabel(): string
    {
        return ucfirst($this->payment_method);
    }
}