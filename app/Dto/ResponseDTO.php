<?php

namespace App\Dto;

use App\Models\User;

class ResponseDTO
{
    public bool $success;
    public ?string $message;
    public ?array $data;
    public ?array $errors;
    public bool $emailVerified;

    public User|array|null $user;
    public ?string $token;

    public ?array $raw;

    public function __construct(array $payload)
    {
        $this->success        = $payload['success'] ?? false;
        $this->message        = $payload['message'] ?? null;
        $this->data           = $payload['data'] ?? null;
        $this->errors         = $payload['errors'] ?? null;
        $this->emailVerified  = $payload['email_verified'] ?? false;

        // 👇 Estas dos claves que necesitas
        $this->user           = $payload['user'] ?? null;
        $this->token          = $payload['token'] ?? null;

        $this->raw = $payload;
    }

    public static function from(array $payload): self
    {
        return new self($payload);
    }
}
