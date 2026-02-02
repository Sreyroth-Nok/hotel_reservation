<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display all reservations
     */
    public function index()
    {
        $reservations = Reservation::with(['guest', 'room.roomType', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new reservation
     */
    public function create()
    {
        $available_rooms = Room::with('roomType')
            ->where('status', 'available')
            ->get();
        
        // Get all guests
        $guests = Guest::orderBy('full_name')->get();

        return view('reservations.create', compact('available_rooms', 'guests'));
    }

    /**
     * Store a newly created guest (for reservation form)
     */
    public function storeGuest(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:guests,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'id_card_number' => 'nullable|string|max:50',
        ]);

        $guest = Guest::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'id_card_number' => $validated['id_card_number'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guest created successfully!',
            'guest' => $guest,
        ]);
    }

    /**
     * Store a newly created reservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,guest_id',
            'room_id' => 'required|exists:rooms,room_id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        DB::beginTransaction();
        try {
            // Get room details
            $room = Room::with('roomType')->findOrFail($validated['room_id']);

            // Create reservation
            $reservation = new Reservation();
            $reservation->guest_id = $validated['guest_id'];
            $reservation->room_id = $validated['room_id'];
            $reservation->check_in = $validated['check_in'];
            $reservation->check_out = $validated['check_out'];
            $reservation->status = 'booked';
            
            // Calculate total price using OOP method
            $reservation->calculateTotalPrice($room->roomType->price_per_night);
            $reservation->save();

            // Update room status
            $room->changeStatus('occupied');

            DB::commit();

            return redirect()
                ->route('reservations.show', $reservation->reservation_id)
                ->with('success', 'Reservation created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to create reservation: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified reservation
     */
    public function show($id)
    {
        $reservation = Reservation::with(['guest', 'room.roomType', 'payments'])
            ->findOrFail($id);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified reservation
     */
    public function edit($id)
    {
        $reservation = Reservation::with(['guest', 'room.roomType'])
            ->findOrFail($id);
        
        $available_rooms = Room::with('roomType')
            ->where('status', 'available')
            ->orWhere('room_id', $reservation->room_id)
            ->get();
        
        $guests = Guest::orderBy('full_name')->get();

        return view('reservations.edit', compact('reservation', 'available_rooms', 'guests'));
    }

    /**
     * Update the specified reservation
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'guest_id' => 'sometimes|exists:guests,guest_id',
            'check_in' => 'sometimes|date',
            'check_out' => 'sometimes|date|after:check_in',
            'status' => 'sometimes|in:booked,checked_in,checked_out,cancelled',
        ]);

        // If dates changed, recalculate price
        if (isset($validated['check_in']) || isset($validated['check_out'])) {
            $reservation->fill($validated);
            $reservation->calculateTotalPrice($reservation->room->roomType->price_per_night);
        }

        $reservation->update($validated);

        return redirect()
            ->route('reservations.show', $reservation->reservation_id)
            ->with('success', 'Reservation updated successfully!');
    }

    /**
     * Check in a reservation
     */
    public function checkIn($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->status !== 'booked') {
            return back()->withErrors(['error' => 'Only booked reservations can be checked in.']);
        }

        $reservation->checkIn();

        return redirect()
            ->route('reservations.show', $reservation->reservation_id)
            ->with('success', 'Guest checked in successfully!');
    }

    /**
     * Check out a reservation
     */
    public function checkOut($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->status !== 'checked_in') {
            return back()->withErrors(['error' => 'Only checked-in reservations can be checked out.']);
        }

        if (!$reservation->isFullyPaid()) {
            return back()->withErrors(['error' => 'Cannot check out. Payment is not complete.']);
        }

        $reservation->checkOut();

        return redirect()
            ->route('reservations.show', $reservation->reservation_id)
            ->with('success', 'Guest checked out successfully!');
    }

    /**
     * 
     * Cancel a reservation
     */
    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if (!in_array($reservation->status, ['booked', 'checked_in'])) {
            return back()->withErrors(['error' => 'Cannot cancel this reservation.']);
        }

        $reservation->cancel();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation cancelled successfully!');
    }

    /**
     * Check room availability
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'room_type_id' => 'nullable|exists:room_types,type_id',
        ]);

        $query = Room::with('roomType')
            ->where('status', 'available')
            ->whereDoesntHave('reservations', function($q) use ($validated) {
                $q->where(function($query) use ($validated) {
                    $query->whereBetween('check_in', [$validated['check_in'], $validated['check_out']])
                          ->orWhereBetween('check_out', [$validated['check_in'], $validated['check_out']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('check_in', '<=', $validated['check_in'])
                                ->where('check_out', '>=', $validated['check_out']);
                          });
                })->whereIn('status', ['booked', 'checked_in']);
            });

        if (isset($validated['room_type_id'])) {
            $query->where('type_id', $validated['room_type_id']);
        }

        $available_rooms = $query->get();

        return response()->json([
            'available' => $available_rooms->count() > 0,
            'rooms' => $available_rooms,
        ]);
    }
}
