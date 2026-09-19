<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Pages
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }


    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,host',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);

        // Regenerate session after authentication
        $request->session()->regenerate();

        // Redirect based on role
        if ($user->role === 'host') {
            return redirect()
                ->route('host.page')
                ->with('success', 'Host account created successfully!');
        }

        return redirect()
            ->route('home')
            ->with('success', 'User account created successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'required|in:user,host',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return back()
                ->withInput($request->only('email', 'role'))
                ->with('error', 'Invalid email or password.');
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Verify Selected Role
        |--------------------------------------------------------------------------
        |
        | Prevent a normal user from selecting "Host" and logging in
        | as a host.
        |
        */

        if ($user->role !== $request->role) {
            Auth::logout();

            return back()
                ->withInput($request->only('email', 'role'))
                ->with('error', 'Wrong login type selected.');
        }

        // Prevent session fixation
        $request->session()->regenerate();

        if ($user->role === 'host') {
            return redirect()
                ->route('host.page')
                ->with('success', 'Host login successful!');
        }

        return redirect()
            ->route('home')
            ->with('success', 'User login successful!');
    }


    /*
    |--------------------------------------------------------------------------
    | Profile Page
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $user = Auth::user();

        $bookings = Booking::with('property')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('profile', compact('user', 'bookings'));
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate current session
        $request->session()->invalidate();

        // Generate a new CSRF token
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Logged out successfully!');
    }
}