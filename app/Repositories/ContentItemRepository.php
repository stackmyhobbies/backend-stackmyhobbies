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
            $query->where('is_active', true);
        }

        return $query->where('id', $id)->firstOrFail();
    }

    //* DONE
    public function index(array $with = [], array $filters = [], ?int $perPage = null)
    {
        $query = ContentItem::query();

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($filters)) {
            // 1. Buscador de texto (se queda igual)
            if (!empty($filters['search'])) {
                $search = '%' . $filters['search'] . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', $search)
                        ->orWhereHas('user', fn($u) => $u->where('email', 'LIKE', $search));
                });
            }

            // 2. Filtros Múltiples (Uso de whereIn)

            // Soporta uno o varios tipos (ej: [1, 2])
            if (isset($filters['content_type_id'])) {
                $ids = is_array($filters['content_type_id']) ? $filters['content_type_id'] : [$filters['content_type_id']];
                $query->whereIn('content_type_id', $ids);
            }

            // Soporta uno o varios estados de progreso
            if (isset($filters['progress_status_id'])) {
                $ids = is_array($filters['progress_status_id']) ? $filters['progress_status_id'] : [$filters['progress_status_id']];
                $query->whereIn('progress_status_id', $ids);
            }

            // 3. Filtro de Tags Múltiples (Lógica de "Cualquiera de estos")
            if (isset($filters['tags']) && !empty($filters['tags'])) {
                // Nos aseguramos de que sea un array (por si el front manda un solo ID)
                $tagIds = is_array($filters['tags']) ? $filters['tags'] : [$filters['tags']];

                $query->whereHas('tags', function ($q) use ($tagIds) {
                    // Importante: especificar la tabla 'tags.id' para evitar ambigüedad
                    $q->whereIn('tags.id', $tagIds);
                });
            }
            // Filtro de usuario (generalmente es uno solo en un select2)
            if (isset($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    //* DONE
    public function indexForUser(?string $user_id, array $with = [], array $filters = [], ?int $perPage = null)
    {
        $query = ContentItem::query();

        if (!empty($with)) {
            $query->with($with);
        }

        // Seguridad: Siempre filtrar por el usuario actual y que esté activo
        $query->where('user_id', $user_id)
            ->where('is_active', true);

        if (!empty($filters)) {
            // Búsqueda por texto
            if (!empty($filters['search'])) {
                $search = '%' . $filters['search'] . '%';
                $query->where('title', 'LIKE', $search);
            }

            // Filtro por tipos (Soportando selección múltiple)
            if (isset($filters['content_type_id'])) {
                $ids = is_array($filters['content_type_id']) ? $filters['content_type_id'] : [$filters['content_type_id']];
                $query->whereIn('content_type_id', $ids);
            }

            // Filtro por estados de progreso (Soportando selección múltiple)
            if (isset($filters['progress_status_id'])) {
                $ids = is_array($filters['progress_status_id']) ? $filters['progress_status_id'] : [$filters['progress_status_id']];
                $query->whereIn('progress_status_id', $ids);
            }

            // --- FILTRO DE TAGS PARA EL USUARIO ---
            if (isset($filters['tags']) && !empty($filters['tags'])) {
                $tagIds = is_array($filters['tags']) ? $filters['tags'] : [$filters['tags']];

                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
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
            ->update(['is_active' => !$this->active]);
        return $contentItem;
    }

    public function destroyForUser(?string $user_id, $id)
    {
        $contentItem = $this->getContentItemById($id, $this->active, $user_id);
        $contentItem->update(['status' => !$this->active]);

        ContentTag::where('content_item_id', $contentItem->id)
            ->update(['is_active' => !$this->active]);
        return $contentItem;
    }
}
