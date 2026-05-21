<?php

namespace App\Repositories;

use App\Interfaces\ContentItemRepositoryInterface;
use App\Models\ContentItem;
use App\Models\ContentTag;

class ContentItemRepository implements ContentItemRepositoryInterface
{
    protected $active = true;

    public function find(string|int $id): ContentItem
    {
        $query = ContentItem::query();

        return $query->where('id', $id)->firstOrFail();
    }

    public function findForUser(?string $user_id, string|int $id): ContentItem
    {
        $query = ContentItem::query();

        if (! empty($user_id)) {
            $query->where('user_id', $user_id);
        }

        $query->where('is_active', true);

        return $query->where('id', $id)->firstOrFail();
    }

    // * DONE
    public function index(array $with = [], array $filters = [], ?int $perPage = null)
    {
        $query = ContentItem::query();

        if (! empty($with)) {
            $query->with($with);
        }

        if (! empty($filters)) {
            if (! empty($filters['search'])) {
                $search = '%'.$filters['search'].'%';
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', $search)
                        ->orWhereHas('user', fn ($u) => $u->where('email', 'LIKE', $search));
                });
            }

            if (isset($filters['content_type'])) {
                $slugs = $this->normalizeFilterValues($filters['content_type']);
                $query->whereHas('contentType', fn ($q) => $q->whereIn('slug', $slugs));
            }

            if (isset($filters['progress'])) {
                $slugs = $this->normalizeFilterValues($filters['progress']);
                $query->whereHas('progressStatus', fn ($q) => $q->whereIn('slug', $slugs));
            }

            if (isset($filters['tags']) && ! empty($filters['tags'])) {
                $tagSlugs = $this->normalizeFilterValues($filters['tags']);

                $query->whereHas('tags', function ($q) use ($tagSlugs) {
                    $q->whereIn('tags.slug', $tagSlugs);
                });
            }

            if (isset($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    // * DONE
    public function indexForUser(?string $user_id, array $with = [], array $filters = [], ?int $perPage = null)
    {
        $query = ContentItem::query();

        if (! empty($with)) {
            $query->with($with);
        }

        $query->where('user_id', $user_id)
            ->where('is_active', true);

        if (! empty($filters)) {
            if (! empty($filters['search'])) {
                $search = strtolower($filters['search']);

                $query->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
            }
            if (isset($filters['content_type'])) {
                $slugs = $this->normalizeFilterValues($filters['content_type']);
                $query->whereHas('contentType', fn ($q) => $q->whereIn('slug', $slugs));
            }

            if (isset($filters['progress'])) {
                $slugs = $this->normalizeFilterValues($filters['progress']);
                $query->whereHas('progressStatus', fn ($q) => $q->whereIn('slug', $slugs));
            }

            if (isset($filters['tags']) && ! empty($filters['tags'])) {
                $tagSlugs = $this->normalizeFilterValues($filters['tags']);

                $query->whereHas('tags', function ($q) use ($tagSlugs) {
                    $q->whereIn('tags.slug', $tagSlugs);
                });
            }
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function show($slug)
    {
        return ContentItem::with(['tags', 'contentType', 'progressStatus'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function showForUser($user_id, $slug)
    {
        return ContentItem::with(['tags', 'contentType', 'progressStatus'])
            ->where('slug', $slug)
            ->where('user_id', $user_id)
            ->where('is_active', $this->active)
            ->firstOrFail();
    }

    public function store(?string $user_id, array $data)
    {
        $content_item = new ContentItem($data);
        $content_item->user_id = $user_id;

        $content_item->save();

        return $content_item;
    }

    public function storeForUser(?string $user_id, array $data)
    {
        $content_item = new ContentItem($data);
        $content_item->user_id = $user_id;
        $content_item->save();

        return $content_item;
    }

    public function update(array $data, ContentItem $contentItem): ContentItem
    {
        $contentItem->update($data);

        return $contentItem->fresh();
    }

    public function updateForUser(array $data, ContentItem $contentItem): ContentItem
    {
        $contentItem->update($data);

        return $contentItem->fresh();
    }

    public function destroy(ContentItem $contentItem): ContentItem
    {
        $contentItem->update(['is_active' => ! $this->active]);

        ContentTag::where('content_item_id', $contentItem->id)
            ->update(['is_active' => ! $this->active]);

        return $contentItem;
    }

    public function destroyForUser(ContentItem $contentItem): ContentItem
    {
        $contentItem->update(['is_active' => ! $this->active]);

        ContentTag::where('content_item_id', $contentItem->id)
            ->update(['is_active' => ! $this->active]);

        return $contentItem;
    }

    private function normalizeFilterValues(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        return explode(',', $value);
    }
}
