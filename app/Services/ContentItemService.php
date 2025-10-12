<?php

namespace App\Services;

use App\Enums\SegmentType;
use App\Interfaces\ContentItemRepositoryInterface;
use App\Models\ContentItem;
use App\Models\ContentTag;
use App\Support\TryCatch;
use Illuminate\Support\Facades\DB;

class ContentItemService
{



    /*TODO PENDIENTE EL STOREFOR USER  */

    public function __construct(private ContentItemRepositoryInterface $contentItemRepository) {}

    public function indexForUser(?string $user_id, array $filters = [], ?int $perPage = null)
    {
        $with =  ['tags', 'contentType', 'contentStatus'];
        return $this->contentItemRepository->indexForUser($user_id, with: $with, filters: $filters, perPage: $perPage);
    }

    public function index(array $filters = [], ?int $perPage = null)
    {

        return $this->contentItemRepository->index(with: ['tags', 'contentType', 'contentStatus'], filters: $filters, perPage: $perPage);
    }

    public function show($slug)
    {
        return $this->contentItemRepository->show($slug);
    }

    public function showForUser($user_id, $slug): ContentItem
    {
        return $this->contentItemRepository->showForUser($user_id, $slug);
    }

    public function store(?string $user_id, array $data)
    {
        return TryCatch::handle(
            function () use ($user_id, $data) {

                /** Para activar content-item inactivo  */
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
                        'contentStatus',
                    ]);
                }


                $contentData = collect($data)->except('tags')->toArray();

                $content_item = $this->contentItemRepository->store($user_id, $contentData);
                $content_item->tags()->sync($data['tags']);
                return $content_item->load([
                    'tags',
                    'contentType',
                    'contentStatus',
                ]);
            }
        );
    }

    public function storeForUser(?string $user_id, array $data)
    {
        return TryCatch::handle(
            function () use ($user_id, $data) {

                /** Para activar content-item inactivo */
                $existing = ContentItem::where('user_id', $user_id)
                    ->where('title', $data['title'])
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
                        'contentStatus',
                    ]);
                }


                $contentData = collect($data)->except('tags')->toArray();

                $content_item = $this->contentItemRepository->storeForUser($user_id, $contentData);
                $content_item->tags()->sync($data['tags']);
                return $content_item->load([
                    'tags',
                    'contentType',
                    'contentStatus',
                ]);
            }
        );
    }


    public function update(array $data, $id)
    {

        return TryCatch::handle(function () use ($data, $id) {
            $tags = $data['tags'] ?? null;
            $contentData = collect($data)->except('tags')->toArray();

            $contentItem = $this->contentItemRepository->update($contentData, $id);

            if ($tags !== null && method_exists($contentItem, 'tags')) {
                $contentItem->tags()->sync($tags);
            }
            return $contentItem;
        });
    }

    public function updateForUser($user_id, array $data, $id): ContentItem
    {

        return TryCatch::handle(function () use ($user_id, $data, $id) {
            $tags = $data['tags'] ?? null;
            $contentData = collect($data)->except('tags')->toArray();

            $contentItem = $this->contentItemRepository->updateForUser($user_id, $contentData, $id);

            if ($tags !== null && method_exists($contentItem, 'tags')) {
                $contentItem->tags()->sync($tags);
            }
            return $contentItem;
        });
    }

    public function destroy($id)
    {
        return TryCatch::handle(function () use ($id) {
            return $this->contentItemRepository->destroy($id);
        });
    }

    public function destroyForUser($user_id, $id)
    {
        return TryCatch::handle(function () use ($user_id, $id) {
            return $this->contentItemRepository->destroyForUser($user_id, $id);
        });
    }
}
