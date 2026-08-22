<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/dashboard');
        }
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function customerCreate()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }

        return Inertia::render('Frontend/Auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Find admin user
        $admin = User::where('email', $request->email)
            ->where('status', 1)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['super admin', 'admin']);
            })
            ->first();

        if (! $admin) {
            throw ValidationException::withMessages([
                'email' => __('Admin account not found or inactive.'),
            ]);
        }

        // 2. Attempt login using ADMIN guard
        if (! Auth::guard('admin')->attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'password' => __('Invalid email or password.'),
            ]);
        }

        // 3. Regenerate session
        $request->session()->regenerate();

        return redirect()->route('super_admin.dashboard');
    }

    public function customerLogin(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)
            ->where('status', 1)
            ->first();

        if ($user) {

            $request->authenticate();
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        throw ValidationException::withMessages([
            'password' => __('Account not found with this email and password.'),
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     Auth::guard('web')->logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     return Redirect::to('/');
    // }

    // public function adminDestroy(Request $request): RedirectResponse
    // {
    //     Auth::guard('admin')->logout();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect()->route('admin-login');
    // }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->regenerate();        // keep admin guard data
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function destroyAdmin(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect('/admin-login');
    }
}
