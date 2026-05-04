<?php

namespace App\Interfaces;

interface ContentItemRepositoryInterface
{
    public function index(array $with = [], array $filters = [], ?int $perPage = null);
    public function indexForUser(?string $user_id, array $with = [], array $filters = [], ?int $perPage = null);

    public function show(string $slug);
    public function showForUser(?string $user_id, string $slug);

    public function store(?string $user_id, array $data);
    public function storeForUser(?string $user_id, array $data);

    public function update(array $data, string | int $id);
    public function updateForUser(?string $user_id, array $data, string | int $id);

    public function destroyForUser(?string $user_id, string|int $id);
    public function destroy(string|int  $id);

    public function getImagePathForUser(?string $user_id, ?string $id);
}