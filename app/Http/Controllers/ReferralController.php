<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferralRequest;
use App\Http\Resources\ReferralCollection;
use App\Mail\ReferralInvite;
use App\Models\Referral;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ReferralController extends Controller
{
    /**
     * Display the list of referrals.
     */
    public function index(): Response
    {
        $referrals = Referral::with('referrer')->latest('id')->paginate(request('per_page', 10));

        return Inertia::render('Referral/Index', [
            'referrals' => new ReferralCollection($referrals),
        ]);
    }

    /**
     * Display the referrals form.
     */
    public function create(): Response
    {
        return Inertia::render('Referral/Create', [
            'status' => session('success'),
        ]);
    }

    /**
     * Save invited user and send inviatation email
     */
    public function store(ReferralRequest $request): RedirectResponse
    {
        $token = Str::uuid()->toString();

        Auth::user()->referals()->create([
            'email' => $request->email,
            'token' => $token,
        ]);

        $referralLink = route('register', ['refer' => $token, 'email' => $request->email]);

        // Send an email to the invited email address with the referral link
        Mail::to($request->email)->send(new ReferralInvite($referralLink));

        return back()->withSuccess('Referral invitation sent successfully!');
    }
}
