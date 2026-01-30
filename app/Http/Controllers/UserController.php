<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Stats
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'staff_users' => User::where('role', 'staff')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
            'phone' => 'required|string|max:20',
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::with(['reservations' => function($query) {
            $query->latest()->limit(10);
        }])->findOrFail($id);

        // User statistics
        $userStats = [
            'total_reservations' => $user->reservations()->count(),
            'active_reservations' => $user->reservations()
                ->whereIn('status', ['booked', 'checked_in'])
                ->count(),
            'completed_reservations' => $user->reservations()
                ->where('status', 'checked_out')
                ->count(),
            'total_spent' => $user->reservations()
                ->where('status', '!=', 'cancelled')
                ->sum('total_price'),
        ];

        return view('users.show', compact('user', 'userStats'));
    }

    /**
     * Show the form for editing the specified user
     */
    // 
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->user_id, 'user_id')
            ],
            'full_name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
            'phone' => 'required|string|max:20',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.show', $user->user_id)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting user with active reservations
        if ($user->reservations()->whereIn('status', ['booked', 'checked_in'])->exists()) {
            return back()->withErrors([
                'error' => 'Cannot delete user with active reservations.'
            ]);
        }

        // Prevent self-deletion
        if ($user->user_id === auth()->id()) {
            return back()->withErrors([
                'error' => 'You cannot delete your own account.'
            ]);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Change user role
     */
    public function changeRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,staff',
        ]);

        $user = User::findOrFail($id);

        // Prevent changing own role
        if ($user->user_id === auth()->id()) {
            return back()->withErrors([
                'error' => 'You cannot change your own role.'
            ]);
        }

        $user->role = $validated['role'];
        $user->save();

        return redirect()
            ->route('users.show', $user->user_id)
            ->with('success', 'User role updated successfully!');
    }

    /**
     * Toggle user active status (optional feature)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-deactivation
        if ($user->user_id === auth()->id()) {
            return back()->withErrors([
                'error' => 'You cannot deactivate your own account.'
            ]);
        }

        // Toggle status (you may need to add an 'active' column to users table)
        // $user->active = !$user->active;
        // $user->save();

        return redirect()
            ->route('users.show', $user->user_id)
            ->with('success', 'User status updated successfully!');
    }

    /**
     * Get users by role (for AJAX requests)
     */
    public function getUsersByRole($role)
    {
        $users = User::where('role', $role)->get();
        return response()->json($users);
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,user_id',
        ]);

        // Prevent deleting current user
        $userIds = array_filter($validated['user_ids'], function($id) {
            return $id != auth()->id();
        });

        // Check for active reservations
        $usersWithActiveReservations = User::whereIn('user_id', $userIds)
            ->whereHas('reservations', function($query) {
                $query->whereIn('status', ['booked', 'checked_in']);
            })
            ->count();

        if ($usersWithActiveReservations > 0) {
            return back()->withErrors([
                'error' => "Cannot delete {$usersWithActiveReservations} user(s) with active reservations."
            ]);
        }

        $deletedCount = User::whereIn('user_id', $userIds)->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "{$deletedCount} user(s) deleted successfully!");
    }
}