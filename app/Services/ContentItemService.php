<?php

namespace App\Services;

use App\Interfaces\ContentItemRepositoryInterface;
use App\Models\ContentItem;
use App\Models\ContentTag;
use App\Support\TryCatch;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ContentItemService
{
    public function __construct(private ContentItemRepositoryInterface $contentItemRepository) {}

    public function indexForUser(?string $user_id, array $filters = [], ?int $perPage = null)
    {
        $with = ['tags', 'contentType', 'progressStatus'];

        return $this->contentItemRepository->indexForUser($user_id, with: $with, filters: $filters, perPage: $perPage);
    }

    public function index(array $filters = [], ?int $perPage = null)
    {
        $with = ['tags', 'contentType', 'progressStatus', 'user'];

        return $this->contentItemRepository->index(
            with: $with,
            filters: $filters,
            perPage: $perPage
        );
    }

    public function show($slug)
    {
        return $this->contentItemRepository->show($slug);
    }

    public function showForUser($user_id, $slug): ContentItem
    {
        return $this->contentItemRepository->showForUser($user_id, $slug);
    }

    public function find(string|int $id): ContentItem
    {
        return $this->contentItemRepository->find($id);
    }

    public function findForUser(?string $user_id, string|int $id): ContentItem
    {
        return $this->contentItemRepository->findForUser($user_id, $id);
    }

    public function store(?string $user_id, array $data)
    {
        return TryCatch::handle(
            function () use ($user_id, $data) {

                $existing = ContentItem::where('title', $data['title'])
                    ->where('segment_type', $data['segment_type'])
                    ->where('segment_number', $data['segment_number'])
                    ->where('status', false)
                    ->first();

                if ($existing) {

                    $contentData = collect($existing)->except('tags')->toArray();

                    $existing->update(['status' => true] + $contentData);

                    ContentTag::where('status', false)
                        ->where('content_item_id', $existing->id)
                        ->update(['status' => true]);

                    return $existing->load([
                        'tags',
                        'contentType',
                        'progressStatus',
                    ]);
                }

                $contentData = collect($data)->except('tags')->toArray();

                $content_item = $this->contentItemRepository->store($user_id, $contentData);
                $content_item->tags()->sync($data['tags']);

                return $content_item->load([
                    'tags',
                    'contentType',
                    'progressStatus',
                ]);
            }
        );
    }

    public function storeForUser(?string $user_id, array $data, $imageFile = null)
    {
        return TryCatch::handle(
            function () use ($user_id, $data, $imageFile) {

                if ($imageFile instanceof UploadedFile) {

                    $result = Cloudinary::uploadApi()->upload(
                        $imageFile->getRealPath(),
                        [
                            'folder' => 'stack_my_hobbies',
                            'resource_type' => 'image',
                            'public_id' => Str::uuid()->toString(),
                        ]
                    );

                    $data['image_path'] = $result['public_id'];
                }
                unset($data['image']);

                $existing = ContentItem::where('user_id', $user_id)
                    ->where('title', $data['title'])
                    ->where('segment_type', $data['segment_type'])
                    ->where('segment_number', $data['segment_number'])
                    ->where('is_active', false)
                    ->first();

                if ($existing) {
                    $updateData = ['is_active' => true];
                    if (isset($data['image_path'])) {
                        $updateData['image_path'] = $data['image_path'];
                    }

                    $existing->update($updateData);

                    ContentTag::where('content_item_id', $existing->id)
                        ->update(['is_active' => true]);

                    return $existing->load(['tags', 'contentType', 'progressStatus']);
                }

                $contentData = collect($data)->except('tags', 'image')->toArray();

                $content_item = $this->contentItemRepository->storeForUser($user_id, $contentData);

                if (isset($data['tags'])) {
                    $content_item->tags()->sync($data['tags']);
                }

                return $content_item->load(['tags', 'contentType', 'progressStatus']);
            }
        );
    }

    public function update(array $data, ContentItem $contentItem): ContentItem
    {
        return TryCatch::handle(function () use ($data, $contentItem) {
            $tags = $data['tags'] ?? null;
            $contentData = collect($data)->except('tags')->toArray();

            $contentItem = $this->contentItemRepository->update($contentData, $contentItem);

            if ($tags !== null && method_exists($contentItem, 'tags')) {
                $contentItem->tags()->sync($tags);
            }

            return $contentItem;
        });
    }

    public function updateForUser(array $data, ContentItem $contentItem, $imageFile = null): ContentItem
    {
        return TryCatch::handle(function () use ($data, $contentItem, $imageFile) {

            if ($imageFile instanceof UploadedFile) {

                if (! empty($contentItem->image_path)) {
                    Cloudinary::uploadApi()->destroy($contentItem->image_path, []);
                }

                $result = Cloudinary::uploadApi()->upload(
                    $imageFile->getRealPath(),
                    [
                        'folder' => 'stack_my_hobbies',
                        'resource_type' => 'image',
                        'public_id' => Str::uuid()->toString(),
                    ]
                );

                $data['image_path'] = $result['public_id'];
            }
            unset($data['image']);

            $tags = $data['tags'] ?? null;
            $contentData = collect($data)->except('tags')->toArray();

            $contentItem = $this->contentItemRepository->updateForUser($contentData, $contentItem);

            if ($tags !== null && method_exists($contentItem, 'tags')) {
                $contentItem->tags()->sync($tags);
            }

            return $contentItem;
        });
    }

    public function destroy(ContentItem $contentItem): ContentItem
    {
        return TryCatch::handle(function () use ($contentItem) {
            return $this->contentItemRepository->destroy($contentItem);
        });
    }

    public function destroyForUser(ContentItem $contentItem): ContentItem
    {
        return TryCatch::handle(function () use ($contentItem) {
            return $this->contentItemRepository->destroyForUser($contentItem);
        });
    }
}
