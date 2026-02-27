<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export reservations to CSV
     */
    public function reservationsCsv(Request $request)
    {
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $reservations = Reservation::with(['guest', 'room.roomType', 'payments'])
            ->when($status, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($dateFrom, function($q) use ($dateFrom) {
                $q->where('check_in', '>=', $dateFrom);
            })
            ->when($dateTo, function($q) use ($dateTo) {
                $q->where('check_out', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reservations_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($reservations) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Guest Name',
                'Email',
                'Phone',
                'Room Number',
                'Room Type',
                'Check In',
                'Check Out',
                'Nights',
                'Total Price',
                'Paid Amount',
                'Balance',
                'Status',
                'Created At',
            ]);

            foreach ($reservations as $reservation) {
                $paidAmount = $reservation->payments->sum('amount');
                $balance = $reservation->total_price - $paidAmount;

                fputcsv($file, [
                    $reservation->reservation_id,
                    $reservation->guest->full_name,
                    $reservation->guest->email,
                    $reservation->guest->phone,
                    $reservation->room->room_number,
                    $reservation->room->roomType->type_name,
                    $reservation->check_in->format('Y-m-d'),
                    $reservation->check_out->format('Y-m-d'),
                    $reservation->nights_count,
                    number_format($reservation->total_price, 2),
                    number_format($paidAmount, 2),
                    number_format($balance, 2),
                    ucfirst(str_replace('_', ' ', $reservation->status)),
                    $reservation->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export reservations to PDF (HTML format for printing)
     */
    public function reservationsPdf(Request $request)
    {
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $reservations = Reservation::with(['guest', 'room.roomType', 'payments'])
            ->when($status, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($dateFrom, function($q) use ($dateFrom) {
                $q->where('check_in', '>=', $dateFrom);
            })
            ->when($dateTo, function($q) use ($dateTo) {
                $q->where('check_out', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('exports.reservations-pdf', compact('reservations'));
    }

    /**
     * Export guests to CSV
     */
    public function guestsCsv(Request $request)
    {
        $guests = Guest::with(['reservations'])
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="guests_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($guests) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Full Name',
                'Email',
                'Phone',
                'Address',
                'ID Card Number',
                'Total Reservations',
                'Created At',
            ]);

            foreach ($guests as $guest) {
                fputcsv($file, [
                    $guest->guest_id,
                    $guest->full_name,
                    $guest->email,
                    $guest->phone,
                    $guest->address ?? 'N/A',
                    $guest->id_card_number ?? 'N/A',
                    $guest->reservations->count(),
                    $guest->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export payments to CSV
     */
    public function paymentsCsv(Request $request)
    {
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $payments = Payment::with(['reservation.guest', 'reservation.room'])
            ->when($status, function($q) use ($status) {
                $q->where('payment_status', $status);
            })
            ->when($dateFrom, function($q) use ($dateFrom) {
                $q->where('payment_date', '>=', $dateFrom);
            })
            ->when($dateTo, function($q) use ($dateTo) {
                $q->where('payment_date', '<=', $dateTo);
            })
            ->orderBy('payment_date', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Add headers
            fputcsv($file, [
                'Payment ID',
                'Reservation ID',
                'Guest Name',
                'Room Number',
                'Amount',
                'Method',
                'Status',
                'Payment Date',
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->payment_id,
                    $payment->reservation_id,
                    $payment->reservation->guest->full_name ?? 'N/A',
                    $payment->reservation->room->room_number ?? 'N/A',
                    number_format($payment->amount, 2),
                    ucfirst($payment->payment_method),
                    ucfirst($payment->payment_status),
                    $payment->payment_date->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export rooms to CSV
     */
    public function roomsCsv(Request $request)
    {
        $status = $request->get('status');
        $typeId = $request->get('type_id');

        $rooms = Room::with(['roomType', 'reservations'])
            ->when($status, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($typeId, function($q) use ($typeId) {
                $q->where('type_id', $typeId);
            })
            ->orderBy('room_number')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rooms_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($rooms) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Add headers
            fputcsv($file, [
                'Room ID',
                'Room Number',
                'Room Type',
                'Price/Night',
                'Capacity',
                'Status',
                'Total Reservations',
                'Description',
            ]);

            foreach ($rooms as $room) {
                fputcsv($file, [
                    $room->room_id,
                    $room->room_number,
                    $room->roomType->type_name,
                    number_format($room->roomType->price_per_night, 2),
                    $room->roomType->capacity,
                    ucfirst($room->status),
                    $room->reservations->count(),
                    $room->description ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
