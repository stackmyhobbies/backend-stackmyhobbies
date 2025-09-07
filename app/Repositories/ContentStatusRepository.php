<?php

namespace App\Repositories;

use App\Interfaces\ContentStatusRepositoryInterface;
use App\Models\ContentStatus;
use Illuminate\Mail\Mailables\Content;

class ContentStatusRepository implements ContentStatusRepositoryInterface
{

    private $active = true;
    public function indexForUser()
    {
        return ContentStatus::where('status', $this->active)->get();
    }


    public function index(?array $with = [], ?array $filters = [], ?int $perPage = null)
    {
        $query = ContentStatus::query();

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

    public function show($id)
    {
        return ContentStatus::where('id', $id)->firstOrFail();
    }

    public function store(array $data)
    {
        return ContentStatus::create($data);
    }

    public function update(array $data, $id)
    {
        $contentStatus = $this->show($id);
        $contentStatus->update($data);

        return $contentStatus->fresh();
    }

    public function destroy($id)
    {
        $contentType = $this->show($id); // Asegura que status sea true y que exista
        return  $contentType->update(['status' => false]);
    }
}
