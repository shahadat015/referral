<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Register', [
            'token' => $request->input('refer'),
            'email' => $request->input('email'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        event(new Registered($user));

        $this->handleReferral($request);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function handleReferral(Request $request)
    {
        // Check if a referral token is provided
        if (! $request->token) {
            return;
        }

        // Find the referral with the provided token
        $referral = Referral::where('token', $request->token)->where('is_registered', false)->first();
        if ($referral) {
            // Update the referral as used and increase the referrer's referral count
            $referral->update(['is_registered' => true]);
            $referral->referrer()->increment('referrals_count');
        }
    }
}
