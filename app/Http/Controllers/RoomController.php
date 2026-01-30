<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display all rooms
     */
    public function index()
    {
        $rooms = Room::with('roomType')->paginate(20);
        
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new room
     */
    public function create()
    {
        $room_types = RoomType::all();
        
        return view('rooms.create', compact('room_types'));
    }

    /**
     * Store a newly created room
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:10|unique:rooms,room_number',
            'type_id' => 'required|exists:room_types,type_id',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $room = Room::create($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Room created successfully!');
    }

    /**
     * Display the specified room
     */
    public function show($id)
    {
        $room = Room::with(['roomType', 'reservations.user'])
            ->findOrFail($id);

        return view('rooms.show', compact('room'));
    }

    /**
     * Update the specified room
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'room_number' => 'sometimes|string|max:10|unique:rooms,room_number,' . $id . ',room_id',
            'type_id' => 'sometimes|exists:room_types,type_id',
            'status' => 'sometimes|in:available,occupied,maintenance',
        ]);

        $room->update($validated);

        return redirect()
            ->route('rooms.show', $room->room_id)
            ->with('success', 'Room updated successfully!');
    }

    /**
     * Change room status
     */
    public function changeStatus($id, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $room = Room::findOrFail($id);
        $room->changeStatus($validated['status']);

        return redirect()
            ->route('rooms.show', $room->room_id)
            ->with('success', 'Room status updated successfully!');
    }

    /**
     * Delete the specified room
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        
        // Check if room has active reservations
        if ($room->reservations()->whereIn('status', ['booked', 'checked_in'])->exists()) {
            return back()->withErrors(['error' => 'Cannot delete room with active reservations.']);
        }

        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Room deleted successfully!');
    }

    /**
     * Get rooms by type
     */
    public function getRoomsByType($type_id)
    {
        $rooms = Room::where('type_id', $type_id)
            ->where('status', 'available')
            ->get();

        return response()->json($rooms);
    }
}