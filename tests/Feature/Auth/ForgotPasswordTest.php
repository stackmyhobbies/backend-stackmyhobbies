<?php

use App\Jobs\SendPasswordResetLinkJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('forgot password con email inexistente devuelve respuesta generica', function () {
    $response = $this->postJson('/api/auth/forgot-password', [
        'email' => 'noexiste@example.com',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Se ha enviado el enlace para restablecer la contraseña, revisa tu correo.',
        ]);
});

test('forgot password con email valido despacha el job', function () {
    Queue::fake();

    $user = User::factory()->create();

    $response = $this->postJson('/api/auth/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Se ha enviado el enlace para restablecer la contraseña, revisa tu correo.',
        ]);

    Queue::assertPushed(SendPasswordResetLinkJob::class);
});

test('forgot password invalida tokens anteriores al reenviar', function () {
    $user = User::factory()->create();

    // Primer envío directo (simula envío anterior)
    Password::sendResetLink(['email' => $user->email]);
    $firstToken = DB::table('password_reset_tokens')->where('email', $user->email)->value('token');
    expect($firstToken)->not->toBeNull();

    // Segundo envío a través del endpoint — el job debe borrar e insertar nuevo token
    $this->postJson('/api/auth/forgot-password', [
        'email' => $user->email,
    ])->assertOk();

    $secondToken = DB::table('password_reset_tokens')->where('email', $user->email)->value('token');
    expect($secondToken)->not->toBe($firstToken);
});

test('forgot password sin email devuelve error de validacion', function () {
    $response = $this->postJson('/api/auth/forgot-password', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
