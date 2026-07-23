<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\ActivityLog;
use App\Models\Cart;
use Throwable;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && ! $user->is_active) {
            ActivityLog::record('login.blocked', 'A login attempt was blocked for suspended account '.$user->email, $user);
            return back()->withErrors(['email' => 'This account has been suspended. Contact an administrator.']);
        }

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            try {
                Cookie::queue('user', $user->username, 6000);

                foreach ($request->session()->pull('guest_cart', []) as $productId => $quantity) {
                    $cartItem = Cart::firstOrNew(['user_id' => $user->id, 'product_id' => (int) $productId]);
                    $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + (int) $quantity;
                    $cartItem->save();
                }

                $user->forceFill(['last_login_at' => now()])->save();
                ActivityLog::record('auth.login', $user->username.' signed in', $user, ['role' => $user->role]);
            } catch (Throwable $exception) {
                error_log(sprintf(
                    '[CoffeeShop login extras] %s: %s',
                    $exception::class,
                    $exception->getMessage()
                ));
            }

            return redirect()->intended($user->canAccessAdmin() ? route('dashboard') : route('products'))
                ->with('success', 'Logged in successfully.');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        ActivityLog::record('user.registered', $user->username.' created a customer account', $user);

        return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
    }

    public function logout()
    {
        if (Auth::check()) {
            ActivityLog::record('auth.logout', Auth::user()->username.' signed out', Auth::user());
        }
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        Cookie::queue(Cookie::forget('user'));
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
