<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials + ['status' => 'active'])) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($request->wantsJson() || $request->ajax()) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => 'success',
                    'token' => $token,
                    'user' => $user,
                    'redirect' => $this->getRedirectUrl($user->role)
                ]);
            }

            return redirect()->intended($this->getRedirectUrl($user->role));
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided credentials do not match our records.'
            ], 401);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:retailer,wholesaler,distributor,nestle',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'region' => 'nullable|string',
            'territory' => 'nullable|string',
            'wholesaler_id' => 'nullable|exists:users,id',
            'distributor_id' => 'nullable|exists:users,id',
            'order_type' => 'nullable|string|in:direct,wholesaler',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Map order_type to order_direct
        if (isset($validated['order_type'])) {
            $validated['order_direct'] = ($validated['order_type'] === 'direct');
            unset($validated['order_type']);
        }

        $user = User::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful',
                'token' => $token,
                'user' => $user
            ], 201);
        }

        return redirect()->route('login')->with('success', 'Registration successful! Please sign in to continue.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success']);
        }

        return redirect('/');
    }

    protected function getRedirectUrl($role)
    {
        return match ($role) {
            'retailer' => '/retailer/dashboard',
            'wholesaler' => '/wholesaler/dashboard',
            'distributor' => '/distributor/dashboard',
            'nestle' => '/nestle/dashboard',
            default => '/',
        };
    }
}
