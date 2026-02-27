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

        // Statistical reports
        $revenue_stats = $this->getRevenueStatistics();
        $reservation_stats = $this->getReservationStatistics();
        $room_price_stats = $this->getRoomPriceStatistics();

        return view('dashboard.index', compact('stats', 'recent_reservations', 'available_rooms', 'monthly_revenue', 'room_type_distribution', 'revenue_stats', 'reservation_stats', 'room_price_stats'));
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

    /**
     * Calculate standard deviation for an array of values
     */
    private function calculateStdDev(array $values): float
    {
        $count = count($values);
        if ($count === 0) {
            return 0;
        }
        
        $average = array_sum($values) / $count;
        $sumSquaredDiff = 0;
        
        foreach ($values as $value) {
            $sumSquaredDiff += pow($value - $average, 2);
        }
        
        return sqrt($sumSquaredDiff / $count);
    }

    /**
     * Get revenue statistics (Sum, Average, Max, Min, Range, Std Dev)
     */
    private function getRevenueStatistics(): array
    {
        $payments = Payment::select('amount')->get();
        $amounts = $payments->pluck('amount')->toArray();
        
        $count = count($amounts);
        $sum = array_sum($amounts);
        $average = $count > 0 ? $sum / $count : 0;
        $max = $count > 0 ? max($amounts) : 0;
        $min = $count > 0 ? min($amounts) : 0;
        $range = $max - $min;
        $std_dev = $this->calculateStdDev($amounts);
        
        return [
            'count' => $count,
            'sum' => round($sum, 2),
            'average' => round($average, 2),
            'max' => round($max, 2),
            'min' => round($min, 2),
            'range' => round($range, 2),
            'std_dev' => round($std_dev, 2),
        ];
    }

    /**
     * Get reservation statistics (Sum, Average, Max, Min, Range, Std Dev)
     */
    private function getReservationStatistics(): array
    {
        $reservations = Reservation::select('total_price', 'check_in', 'check_out')->get();
        
        // Total price statistics
        $prices = $reservations->pluck('total_price')->toArray();
        $price_count = count($prices);
        $price_sum = array_sum($prices);
        $price_average = $price_count > 0 ? $price_sum / $price_count : 0;
        $price_max = $price_count > 0 ? max($prices) : 0;
        $price_min = $price_count > 0 ? min($prices) : 0;
        $price_range = $price_max - $price_min;
        $price_std_dev = $this->calculateStdDev($prices);
        
        // Length of stay statistics
        $stays = $reservations->map(function($res) {
            if ($res->check_in && $res->check_out) {
                return $res->check_out->diffInDays($res->check_in);
            }
            return 0;
        })->toArray();
        $filtered_stays = array_filter($stays);
        $stay_count = count($filtered_stays);
        $stay_sum = array_sum($filtered_stays);
        $stay_average = $stay_count > 0 ? $stay_sum / $stay_count : 0;
        $stay_max = $stay_count > 0 ? max($filtered_stays) : 0;
        $stay_min = $stay_count > 0 ? min($filtered_stays) : 0;
        $stay_range = $stay_max - $stay_min;
        $stay_std_dev = $this->calculateStdDev($filtered_stays);
        
        return [
            'total_reservations' => $reservations->count(),
            'price' => [
                'sum' => round($price_sum, 2),
                'average' => round($price_average, 2),
                'max' => round($price_max, 2),
                'min' => round($price_min, 2),
                'range' => round($price_range, 2),
                'std_dev' => round($price_std_dev, 2),
            ],
            'length_of_stay' => [
                'sum' => round($stay_sum, 2),
                'average' => round($stay_average, 2),
                'max' => round($stay_max, 2),
                'min' => round($stay_min, 2),
                'range' => round($stay_range, 2),
                'std_dev' => round($stay_std_dev, 2),
            ],
        ];
    }

    /**
     * Get room price statistics (Sum, Average, Max, Min, Range, Std Dev)
     */
    private function getRoomPriceStatistics(): array
    {
        $room_types = RoomType::select('price_per_night')->get();
        $prices = $room_types->pluck('price_per_night')->toArray();
        
        $count = count($prices);
        $sum = array_sum($prices);
        $average = $count > 0 ? $sum / $count : 0;
        $max = $count > 0 ? max($prices) : 0;
        $min = $count > 0 ? min($prices) : 0;
        $range = $max - $min;
        $std_dev = $this->calculateStdDev($prices);
        
        return [
            'count' => $count,
            'sum' => round($sum, 2),
            'average' => round($average, 2),
            'max' => round($max, 2),
            'min' => round($min, 2),
            'range' => round($range, 2),
            'std_dev' => round($std_dev, 2),
        ];
    }
}