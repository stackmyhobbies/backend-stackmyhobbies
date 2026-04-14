<?php

namespace App\Repositories;

use App\Interfaces\ContentTypeRepositoryInterface;
use App\Models\ContentType;
use Illuminate\Database\Eloquent\Collection;

class ContentTypeRepository implements ContentTypeRepositoryInterface
{


    private $active = true;

    public function indexForUser(): Collection
    {
        return ContentType::where("status", $this->active)->get();
    }

    public function index(?array $with = [], ?array $filters = [], ?int $perPage = null)
    {

        $query = ContentType::query();

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

    public function getById($id)
    {
        return ContentType::where('id', $id)->where('status', true)->firstOrFail();
    }

    public function store(array $data)
    {
        return ContentType::create($data);
    }
    public function update(array $data, $id)
    {
        $contentType = ContentType::findOrFail($id);
        $contentType->update($data);

        return $contentType->fresh();
    }

    public function delete($id)
    {
        $contentType = $this->getById($id); // Asegura que status sea true y que exista
        return  $contentType->update(['status' => false]);
    }
}
