<?php

use App\Mail\ReferralInvite;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('create referrals page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/referrals/create');

    $response->assertStatus(200);
});

it('sends referral invitation email', function () {
    Mail::fake();

    // Create the referrer user
    $referrer = User::factory()->create();

    // Perform the request to invite the user
    $this->actingAs($referrer)
        ->post('/referrals/store', ['email' => 'test@example.com'])
        ->assertSessionHas('success');

    // Check that an invitation email queued
    Mail::assertQueued(ReferralInvite::class);
});

it('does not allow inviting an already registered user', function () {
    // Create an existing user
    $user = User::factory()->create();
    $existingUser = User::factory()->create();

    // Perform the request to invite the user
    $response = $this->actingAs($user)
        ->post('/referrals/store', ['email' => $existingUser->email]);

    // Assert that the user cannot be invited and receives an error message
    $response->assertSessionHasErrors(['email']);
});

it('does not allow inviting an already invited user', function () {
    // Create an existing user
    $user = User::factory()->create();
    $alreadyInvitedUser = Referral::factory()->create();

    // Perform the request to invite the user
    $response = $this->actingAs($user)
        ->post('/referrals/store', ['email' => $alreadyInvitedUser->email]);

    // Assert that the user cannot be invited and receives an error message
    $response->assertSessionHasErrors(['email' => 'This user has already been invited.']);
});

it('prevents referral invitation when the limit is reached', function () {
    // Create a user with the referral count equal to the limit (10)
    $user = User::factory()->create(['referrals_count' => 10]);

    // Perform the request to invite a user
    $response = $this->actingAs($user)
        ->post('/referrals/store', ['email' => 'test@example.com']);

    // Assert that the referral invitation is prevented and error message is set
    $response->assertSessionHasErrors(['email' => 'You have reached the referral invitation limit.']);
});

it('tracks successful referral', function () {
    // Create the referrer user
    $referrer = User::factory()->create();

    // Generate a referral record
    $referral = Referral::factory()->create([
        'referrer_id' => $referrer->id,
    ]);

    // Register the referred user using the referral link
    $this->post('/register', ['name' => 'Shahadat', 'email' => $referral->email, 'password' => 'password', 'password_confirmation' => 'password', 'token' => $referral->token])
        ->assertRedirect('/dashboard');

    // Check that the referral is marked as completed
    $referral->refresh();
    expect($referral->is_registered)->toBeTrue();

    // Check that the referrer's referral count increased
    $referrer->refresh();
    expect($referrer->referrals_count)->toBe(1);
});

it('does not track referral for already registered user', function () {
    // Create the referrer user
    $referrer = User::factory()->create();

    // Generate a referral record
    $referral = Referral::factory()->create([
        'referrer_id' => $referrer->id,
    ]);

    // Register the referred user using the referral link
    $this->post('/register', ['name' => 'Shahadat', 'email' => $referral->email, 'password' => 'password', 'password_confirmation' => 'password', 'token' => $referral->token])
        ->assertRedirect('/dashboard');

    // Attempt to register the referred user again using the same referral link
    $this->post('/register', ['name' => 'Shahadat', 'email' => $referral->email, 'password' => 'password', 'password_confirmation' => 'password', 'token' => $referral->token])
        ->assertRedirect('/dashboard');

    // Check that the referral is still marked as completed
    $referral->refresh();
    expect($referral->is_registered)->toBeTrue();

    // Check that the referrer's referral count did not increase
    $referrer->refresh();
    expect($referrer->referrals_count)->toBe(1);
});

it('only admin can see referrals list page', function () {
    // Create an admin user
    $user = User::factory()->admin()->create();

    $response = $this
        ->actingAs($user)
        ->get('/admin/referrals');

    $response->assertStatus(200);
});

it('normal users can not see referral list page', function () {
    // Create a normal user
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/admin/referrals');

    $response->assertStatus(302)
        ->assertRedirect('/dashboard');
});
