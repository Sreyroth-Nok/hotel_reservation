<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm() {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return $this->redirectBasedOnRole();
        }

        return back()->withErrors(['username' => 'Invalid credentials.'])->withInput();
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectBasedOnRole() {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return redirect()->route('dashboard')->with('success', 'Welcome back, Admin ' . $user->full_name);
        } elseif ($user->isStaff()) {
            return redirect()->route('dashboard')->with('success', 'Welcome back, Staff ' . $user->full_name);
        }
        
        return redirect()->route('dashboard');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}