<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Notifications\AdminNewUserAlertNotification;
use App\Notifications\UserRegistrationPendingNotification;
use Illuminate\Support\Facades\Notification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->notify(new UserRegistrationPendingNotification($user));

        $adminEmail = 'bdswapan@gmail.com';
        Notification::route('mail', $adminEmail)
        ->notify(new AdminNewUserAlertNotification($user));

        event(new Registered($user));

        // Do not auto-login the user. Require email verification first.
        // A verification email will be sent because the Registered event was fired.
        // Redirect to the verification notice with a status message to show instructions.
        return redirect()->route('login')->with('status', 'Registration successful! Your account is pending admin approval. You will receive an email once approved.');
}
}
