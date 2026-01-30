<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display all payments
     */
    public function index()
    {
        $payments = Payment::with('reservation.user')
            ->orderBy('payment_date', 'desc')
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create($reservation_id)
    {
        $reservation = Reservation::with(['user', 'room.roomType', 'payments'])
            ->findOrFail($reservation_id);

        return view('payments.create', compact('reservation'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,online',
        ]);

        DB::beginTransaction();
        try {
            $reservation = Reservation::findOrFail($validated['reservation_id']);

            // Check if payment amount is valid
            $remaining_balance = $reservation->getRemainingBalance();
            
            if ($validated['amount'] > $remaining_balance) {
                return back()
                    ->withErrors(['amount' => 'Payment amount exceeds remaining balance.'])
                    ->withInput();
            }

            // Create payment
            $payment = Payment::create([
                'reservation_id' => $validated['reservation_id'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('reservations.show', $reservation->reservation_id)
                ->with('success', 'Payment processed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to process payment: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified payment
     */
    public function show($id)
    {
        $payment = Payment::with('reservation.user')
            ->findOrFail($id);

        return view('payments.show', compact('payment'));
    }

    /**
     * Get payment summary for a reservation
     */
    public function getPaymentSummary($reservation_id)
    {
        $reservation = Reservation::with('payments')->findOrFail($reservation_id);

        return response()->json([
            'total_price' => $reservation->total_price,
            'total_paid' => $reservation->getTotalPaidAmount(),
            'remaining_balance' => $reservation->getRemainingBalance(),
            'is_fully_paid' => $reservation->isFullyPaid(),
            'payments' => $reservation->payments,
        ]);
    }

    /**
     * Generate payment receipt
     */
    public function generateReceipt($id)
    {
        $payment = Payment::with(['reservation.user', 'reservation.room.roomType'])
            ->findOrFail($id);

        // Generate PDF receipt (you would use a PDF library here)
        return view('payments.receipt', compact('payment'));
    }
}