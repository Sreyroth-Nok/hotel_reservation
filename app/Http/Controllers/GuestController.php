<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Services\AuditService;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display all guests with search functionality
     */
    public function index(Request $request)
    {
        $query = $request->get('search');
        
        $guests = Guest::when($query, function($q) use ($query) {
                $q->where('full_name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%")
                  ->orWhere('id_card_number', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends(['search' => $query]);
        
        return view('guests.index', compact('guests', 'query'));
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

        // Log the action
        AuditService::logCreate($guest, "Created guest: {$guest->full_name}");

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
        $oldValues = $guest->toArray();

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

        // Log the action
        AuditService::logUpdate($guest, $oldValues, "Updated guest: {$guest->full_name}");

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

        $guestName = $guest->full_name;
        
        // Log the action before deletion
        AuditService::logDelete($guest, "Deleted guest: {$guestName}");
        
        $guest->delete();

        return redirect()
            ->route('guests.index')
            ->with('success', 'Guest deleted successfully!');
    }
}
