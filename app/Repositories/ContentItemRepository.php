<?php

namespace App\Repositories;

use App\Interfaces\ContentItemRepositoryInterface;
use App\Models\ContentItem;
use App\Models\ContentTag;
use Illuminate\Support\Facades\DB;

class ContentItemRepository implements ContentItemRepositoryInterface
{

    protected $active = true;

    private function getContentItemById($id)
    {
        return ContentItem::where('id', $id)->where('status', $this->active)->firstOrFail();
    }

    public function index(array $with = [], array $filters = [], ?int $perPage = null)
    {
        $query = ContentItem::query();

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        if ($perPage) {
            return $query->where('status', $this->active)->paginate($perPage);
        }

        return $query->where('status', $this->active)->get();
        // return $query->get();
    }

    public function show($slug)
    {
        return ContentItem::with(['tags', 'contentType', 'contentStatus'])
            ->where('slug', $slug)
            ->where('status', $this->active)
            ->firstOrFail();
    }

    public function store(array $data)
    {
        $content = new ContentItem($data); // Asigna todos los atributos
        $content->save(); // Aquí se generará el slug correctamente
        return $content;
    }

    public function update(array $data, $id)
    {
        $contentItem = $this->getContentItemById($id);
        $contentItem->update($data);
        return $contentItem->fresh();
    }


    public function destroy($id)
    {
        $contentItem = $this->getContentItemById($id);
        $contentItem->update(['status' => !$this->active]);

        ContentTag::where('content_item_id', $contentItem->id)
            ->update(['status' => !$this->active]);
        return $contentItem;
    }
}
