<?php

namespace App\Interfaces;

use App\Models\ContentItem;

interface ContentItemRepositoryInterface
{
    public function index(array $with = [], array $filters = [], ?int $perPage = null);

    public function indexForUser(?string $user_id, array $with = [], array $filters = [], ?int $perPage = null);

    public function show(string $slug);

    public function showForUser(?string $user_id, string $slug);

    public function store(?string $user_id, array $data);

    public function storeForUser(?string $user_id, array $data);

    public function find(string|int $id): ContentItem;

    public function findForUser(?string $user_id, string|int $id): ContentItem;

    public function update(array $data, ContentItem $contentItem): ContentItem;

    public function updateForUser(array $data, ContentItem $contentItem): ContentItem;

    public function destroy(ContentItem $contentItem): ContentItem;

    public function destroyForUser(ContentItem $contentItem): ContentItem;
}
