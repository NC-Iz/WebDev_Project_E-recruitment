<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Get the post login redirect path based on user role.
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->role === 'employer') {
            return '/employer/jobs';
        } elseif ($user->role === 'jobseeker') {
            return '/jobseeker/jobs';
        }

        // Default fallback
        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * The user has logged out of the application.
     */
    protected function loggedOut(Request $request)
    {
        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Clear all session data
        $request->session()->flush();

        return redirect('/');
    }
}
