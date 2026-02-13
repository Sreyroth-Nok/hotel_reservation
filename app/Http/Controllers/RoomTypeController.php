<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roomTypes = RoomType::all();
        return view('room_types.index', compact('roomTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('room_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_name' => 'required|string|max:255|unique:room_types',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|string',
        ]);

        RoomType::create($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Room type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomType $roomType)
    {
        return view('room_types.edit', compact('roomType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'type_name' => 'required|string|max:255|unique:room_types,type_name,' . $roomType->type_id . ',type_id',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|string',
        ]);

        $roomType->update($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Room type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        $roomType->delete();

        return redirect()->route('room-types.index')
            ->with('success', 'Room type deleted successfully.');
    }
}
