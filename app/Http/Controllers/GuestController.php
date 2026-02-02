<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display all guests
     */
    public function index()
    {
        $guests = Guest::orderBy('created_at', 'desc')->paginate(20);
        return view('guests.index', compact('guests'));
    }

    /**
     * Show the form for creating a new guest
     */
    public function create()
    {
        return view('guests.create');
    }

    /**
     * Store a newly created guest
     */
    public function store(Request $request)
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

        return redirect()
            ->route('guests.index')
            ->with('success', 'Guest created successfully!');
    }

    /**
     * Display the specified guest
     */
    public function show($id)
    {
        $guest = Guest::findOrFail($id);
        $reservations = $guest->reservations()->with(['room.roomType', 'payments'])->paginate(10);
        return view('guests.show', compact('guest', 'reservations'));
    }

    /**
     * Show the form for editing the specified guest
     */
    public function edit($id)
    {
        $guest = Guest::findOrFail($id);
        return view('guests.edit', compact('guest'));
    }

    /**
     * Update the specified guest
     */
    public function update(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:guests,email,' . $guest->guest_id . ',guest_id',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'id_card_number' => 'nullable|string|max:50',
        ]);

        $guest->update([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'id_card_number' => $validated['id_card_number'] ?? null,
        ]);

        return redirect()
            ->route('guests.index')
            ->with('success', 'Guest updated successfully!');
    }

    /**
     * Remove the specified guest
     */
    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);

        // Check if guest has any active reservations
        $hasActiveReservations = $guest->reservations()
            ->whereIn('status', ['booked', 'checked_in'])
            ->exists();

        if ($hasActiveReservations) {
            return back()->withErrors(['error' => 'Cannot delete guest with active reservations.']);
        }

        $guest->delete();

        return redirect()
            ->route('guests.index')
            ->with('success', 'Guest deleted successfully!');
    }
}
