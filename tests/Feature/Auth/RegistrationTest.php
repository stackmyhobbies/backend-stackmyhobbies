<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('registro genera token de verificacion', function () {
    Queue::fake();

    $response = $this->postJson('/api/auth/sign-up', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertCreated();

    $user = User::where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->email_verification_token)->not->toBeNull();
});

test('verificacion con token valido marca email como verificado', function () {
    Queue::fake();

    $user = User::factory()->unverified()->create([
        'email_verification_token' => 'test-token-hash',
    ]);

    // Construir la URL firmada usando el mismo hash que el token
    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => 'test-token-hash']
    );

    $response = $this->get($url);

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeTrue();
    expect($user->email_verification_token)->toBeNull();
});

test('verificacion con token invalido devuelve error', function () {
    $user = User::factory()->unverified()->create([
        'email_verification_token' => 'token-valido',
    ]);

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => 'token-invalido']
    );

    $response = $this->get($url);

    $response->assertStatus(400)
        ->assertJson(['message' => 'Enlace de verificación inválido.']);

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeFalse();
});

test('verificacion sin token en BD usa hash de email como fallback', function () {
    $user = User::factory()->unverified()->create([
        'email_verification_token' => null,
    ]);

    $hash = sha1($user->getEmailForVerification());

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => $hash]
    );

    $this->get($url);

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeTrue();
});
