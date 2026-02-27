<?php

namespace App\Services;

use App\Models\Reservation;
use App\Mail\ReservationConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send reservation confirmation email
     */
    public static function sendReservationConfirmation(Reservation $reservation): bool
    {
        try {
            // Load relationships if not already loaded
            $reservation->load(['guest', 'room.roomType']);
            
            Mail::to($reservation->guest->email)
                ->send(new ReservationConfirmation($reservation));
            
            Log::info("Reservation confirmation email sent to {$reservation->guest->email} for reservation #{$reservation->reservation_id}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send reservation confirmation email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send check-in confirmation email
     */
    public static function sendCheckInConfirmation(Reservation $reservation): bool
    {
        try {
            $reservation->load(['guest', 'room.roomType']);
            
            Mail::to($reservation->guest->email)
                ->send(new \App\Mail\CheckInConfirmation($reservation));
            
            Log::info("Check-in confirmation email sent to {$reservation->guest->email} for reservation #{$reservation->reservation_id}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send check-in confirmation email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send check-out confirmation email
     */
    public static function sendCheckOutConfirmation(Reservation $reservation): bool
    {
        try {
            $reservation->load(['guest', 'room.roomType', 'payments']);
            
            Mail::to($reservation->guest->email)
                ->send(new \App\Mail\CheckOutConfirmation($reservation));
            
            Log::info("Check-out confirmation email sent to {$reservation->guest->email} for reservation #{$reservation->reservation_id}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send check-out confirmation email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send cancellation email
     */
    public static function sendCancellationNotice(Reservation $reservation): bool
    {
        try {
            $reservation->load(['guest', 'room.roomType']);
            
            Mail::to($reservation->guest->email)
                ->send(new \App\Mail\ReservationCancelled($reservation));
            
            Log::info("Cancellation email sent to {$reservation->guest->email} for reservation #{$reservation->reservation_id}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send cancellation email: " . $e->getMessage());
            return false;
        }
    }
}
