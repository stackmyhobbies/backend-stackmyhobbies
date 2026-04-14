<?php

namespace App\Interfaces;

interface ContentItemRepositoryInterface
{
    public function index(array $with = [], array $filters = [], ?int $perPage = null);
    public function indexForUser(?string $user_id, array $with = [], array $filters = [], ?int $perPage = null);

    public function show($slug);
    public function showForUser(?string $user_id, $slug);

    public function store(?string $user_id, array $data);
    public function storeForUser(?string $user_id, array $data);

    public function update(array $data, $id);
    public function updateForUser(?string $user_id, array $data, $id);

    public function destroyForUser(?string $user_id, $id);
    public function destroy($id);
}
