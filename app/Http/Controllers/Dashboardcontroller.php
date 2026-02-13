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

        // Monthly revenue data for bar chart (last 12 months)
        $monthly_revenue = $this->getMonthlyRevenueData();

        // Room type distribution for pie chart
        $room_type_distribution = $this->getRoomTypeDistribution();

        return view('dashboard.index', compact('stats', 'recent_reservations', 'available_rooms', 'monthly_revenue', 'room_type_distribution'));
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

    /**
     * Get monthly revenue data for bar chart (last 12 months)
     */
    private function getMonthlyRevenueData(): array
    {
        $months = [];
        $revenues = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $revenue = Payment::whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
            $revenues[] = (float) $revenue;
        }
        
        return [
            'months' => $months,
            'revenues' => $revenues,
        ];
    }

    /**
     * Get room type distribution for pie chart
     */
    private function getRoomTypeDistribution(): array
    {
        $room_types = RoomType::withCount('rooms')->get();
        
        $labels = $room_types->pluck('type_name')->toArray();
        $counts = $room_types->pluck('rooms_count')->toArray();
        $colors = [
            '#8B5CF6', // Purple
            '#F59E0B', // Amber
            '#10B981', // Emerald
            '#3B82F6', // Blue
            '#EF4444', // Red
            '#EC4899', // Pink
        ];
        
        return [
            'labels' => $labels,
            'counts' => $counts,
            'colors' => array_slice($colors, 0, count($labels)),
        ];
    }
}