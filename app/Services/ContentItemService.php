<?php

namespace App\Services;


use App\Interfaces\ContentItemRepositoryInterface;
use App\Models\ContentItem;
use App\Models\ContentTag;
use App\Support\TryCatch;


class ContentItemService
{



    /*TODO PENDIENTE EL STOREFOR USER  */

    public function __construct(private ContentItemRepositoryInterface $contentItemRepository) {}

    public function indexForUser(?string $user_id, array $filters = [], ?int $perPage = null)
    {
        $with =  ['tags', 'contentType', 'progressStatus'];
        return $this->contentItemRepository->indexForUser($user_id, with: $with, filters: $filters, perPage: $perPage);
    }

    public function index(array $filters = [], ?int $perPage = null)
    {
        // Incluimos 'user' para que el admin vea el dueño del item
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

    // Añadimos ?UploadedFile $imageFile como parámetro
    public function storeForUser(?string $user_id, array $data, $imageFile = null)
    {
        return TryCatch::handle(
            function () use ($user_id, $data, $imageFile) {

                // 1. Manejo de Cloudinary
                if ($imageFile) {
                    $upload = $imageFile->storeOnCloudinary('stack_my_hobbies');
                    $data['image_path'] = $upload->getPublicId();
                }

                // 2. Lógica de reactivación (Item existente)
                $existing = ContentItem::where('user_id', $user_id)
                    ->where('title', $data['title'])
                    ->where('segment_type', $data['segment_type'])
                    ->where('segment_number', $data['segment_number'])
                    ->where('is_active', false)
                    ->first();

                if ($existing) {
                    // Si existe, actualizamos status y el posible nuevo image_path
                    $updateData = ['is_active' => true];
                    if (isset($data['image_path'])) {
                        $updateData['image_path'] = $data['image_path'];
                    }

                    $existing->update($updateData);

                    // Reactivamos tags
                    ContentTag::where('content_item_id', $existing->id)
                        ->update(['is_active' => true]);

                    return $existing->load(['tags', 'contentType', 'progressStatus']);
                }

                // 3. Creación de item nuevo
                $contentData = collect($data)->except('tags')->toArray();

                $content_item = $this->contentItemRepository->storeForUser($user_id, $contentData);

                if (isset($data['tags'])) {
                    $content_item->tags()->sync($data['tags']);
                }

                return $content_item->load(['tags', 'contentType', 'progressStatus']);
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
