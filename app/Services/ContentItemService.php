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

    protected ContentItemRepositoryInterface $_contentItemRepository;
    public function __construct(ContentItemRepositoryInterface $contentItemRepository)
    {
        $this->_contentItemRepository = $contentItemRepository;
    }

    public function index(array $filters = [], ?int $perPage = null)
    {

        return $this->_contentItemRepository->index(with: ['tags', 'contentType', 'contentStatus'], filters: $filters, perPage: $perPage);
    }

    public function show($slug)
    {
        return $this->_contentItemRepository->show($slug);
    }

    public function store(array $data)
    {
        return TryCatch::handle(
            function () use ($data) {
                $existing = ContentItem::where('user_id', $data['user_id'])
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

                $content_item = $this->_contentItemRepository->store($contentData);
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

            $contentItem = $this->_contentItemRepository->update($contentData, $id);

            if ($tags !== null && method_exists($contentItem, 'tags')) {
                $contentItem->tags()->sync($tags);
            }
            return $contentItem;
        });
    }

    public function destroy($id)
    {
        return TryCatch::handle(function () use ($id) {
            return $this->_contentItemRepository->destroy($id);
        });
    }
}
