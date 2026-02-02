<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard overview
     */
    public function index()
    {
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'active_bookings' => Reservation::whereIn('status', ['booked', 'checked_in'])->count(),
            'monthly_revenue' => Payment::whereMonth('payment_date', Carbon::now()->month)->sum('amount'),
            'occupancy_rate' => $this->calculateOccupancyRate(),
        ];

        $recent_reservations = Reservation::with(['guest', 'room.roomType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $available_rooms = Room::with('roomType')
            ->where('status', 'available')
            ->get();

        return view('dashboard.index', compact('stats', 'recent_reservations', 'available_rooms'));
    }

    /**
     * Calculate occupancy rate
     */
    private function calculateOccupancyRate(): float
    {
        $total_rooms = Room::count();
        $occupied_rooms = Room::where('status', 'occupied')->count();
        
        if ($total_rooms === 0) {
            return 0;
        }
        
        return round(($occupied_rooms / $total_rooms) * 100, 2);
    }

    /**
     * Get monthly statistics
     */
    public function getMonthlyStats()
    {
        $current_month = Carbon::now();
        $previous_month = Carbon::now()->subMonth();

        $current_revenue = Payment::whereMonth('payment_date', $current_month->month)
            ->whereYear('payment_date', $current_month->year)
            ->sum('amount');

        $previous_revenue = Payment::whereMonth('payment_date', $previous_month->month)
            ->whereYear('payment_date', $previous_month->year)
            ->sum('amount');

        $revenue_growth = $previous_revenue > 0 
            ? (($current_revenue - $previous_revenue) / $previous_revenue) * 100 
            : 0;

        return [
            'current_revenue' => $current_revenue,
            'previous_revenue' => $previous_revenue,
            'revenue_growth' => round($revenue_growth, 2),
        ];
    }
}