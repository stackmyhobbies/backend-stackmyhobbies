<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('reenvio con email inexistente devuelve respuesta generica', function () {
    $response = $this->postJson('/api/email/verify/resend', [
        'email' => 'noexiste@example.com',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Si el correo existe en nuestra base de datos, te enviaremos un enlace de verificación.',
        ]);
});

test('reenvio con email ya verificado devuelve respuesta generica', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->postJson('/api/email/verify/resend', [
        'email' => $user->email,
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Si el correo existe en nuestra base de datos, te enviaremos un enlace de verificación.',
        ]);
});

test('reenvio exitoso rota el token de verificacion', function () {
    Queue::fake();

    $user = User::factory()->unverified()->create();

    $this->postJson('/api/email/verify/resend', [
        'email' => $user->email,
    ])->assertOk();

    $user->refresh();
    $token = $user->email_verification_token;

    expect($token)->not->toBeNull();

    // Un segundo reenvío debe rotar el token
    $this->postJson('/api/email/verify/resend', [
        'email' => $user->email,
    ])->assertOk();

    $user->refresh();

    expect($user->email_verification_token)->not->toBe($token);
});

test('reenvio sin email devuelve error de validacion', function () {
    $response = $this->postJson('/api/email/verify/resend', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('reenvio con email invalido devuelve error de validacion', function () {
    $response = $this->postJson('/api/email/verify/resend', [
        'email' => 'no-es-un-email',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
