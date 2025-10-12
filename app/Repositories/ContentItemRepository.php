<?php

namespace App\Repositories;

use App\Interfaces\ContentItemRepositoryInterface;
use App\Models\ContentItem;
use App\Models\ContentTag;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\DB;

class ContentItemRepository implements ContentItemRepositoryInterface
{


    //TODO Agregar el user_id para consultas
    protected $active = true;

    private function getContentItemById($id, $is_active = false, $user_id = null)
    {
        $query = ContentItem::query();

        if (!empty($user_id)) {
            $query->where('user_id', $user_id);
        }

        if ($is_active) {
            $query->where('status', true);
        }

        return $query->where('id', $id)->firstOrFail();
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
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function indexForUser(?string $user_id, array $with = [], array $filters = [], ?int $perPage = null)
    {
        $query = ContentItem::query();

        if (!empty($with)) {
            $query->with($with);
        }


        //TODO CHange and to OR para lo filtro y agregar like
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                $query->where($field, $value);
            }
        }


        //? Ejemplo

        // $searchableFields = ['nombre', 'apellido', 'email', 'cedula'];


        // if (!empty($filters)) {
        //     foreach ($filters as $field => $value) {
        //         if ($field === 'search') {
        //             $query->where(function ($q) use ($value, $searchableFields) {
        //                 // Loop through the defined searchable fields
        //                 foreach ($searchableFields as $searchField) {
        //                     $q->orWhere($searchField, 'LIKE', '%' . $value . '%');
        //                 }
        //             });
        //         } else {
        //             // Apply a simple 'where' clause for all other filters
        //             $query->where($field, $value);
        //         }
        //     }
        // }

        if ($perPage) {
            return $query->where('user_id', $user_id)->where('status', $this->active)->paginate($perPage);
        }

        return $query->where('user_id', $user_id)->where('status', $this->active)->get();
    }

    public function show($slug)
    {
        return ContentItem::with(['tags', 'contentType', 'contentStatus'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function showForUser($user_id, $slug)
    {
        return ContentItem::with(['tags', 'contentType', 'contentStatus'])
            ->where('slug', $slug)
            ->where('user_id', $user_id)
            ->where('status', $this->active)
            ->firstOrFail();
    }

    public function store(?string $user_id, array $data)
    {
        $content_item = new ContentItem($data);
        $content_item->user_id = $user_id;

        // Asigna todos los atributos
        $content_item->save(); // Aquí se generará el slug correctamente
        return $content_item;
    }

    public function storeForUser(?string $user_id, array $data)
    {
        $content_item = new ContentItem($data);
        $content_item->user_id = $user_id;
        $content_item->save();
        return $content_item;
    }

    public function update(array $data, $id)
    {
        $contentItem = $this->getContentItemById($id);
        $contentItem->update($data);
        return $contentItem->fresh();
    }

    public function updateForUser(?string $user_id, array $data, $id)
    {
        $contentItem = $this->getContentItemById($id, $this->active, $user_id);
        $contentItem->updateForUser($user_id, $data, $id);
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

    public function destroyForUser(?string $user_id, $id)
    {
        $contentItem = $this->getContentItemById($id, $this->active, $user_id);
        $contentItem->update(['status' => !$this->active]);

        ContentTag::where('content_item_id', $contentItem->id)
            ->update(['status' => !$this->active]);
        return $contentItem;
    }
}
