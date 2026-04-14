<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Models\ContentItem;
use App\Models\ContentTag;
use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{


    private $active = true;

    public function indexForUser()
    {
        return Tag::where("status", $this->active)->get();
    }


    public function index(?array $with = [], ?array $filters = [], ?int $perPage = null)
    {

        $query = Tag::query();

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
    //*todo aqui quedaste -> consulta de tag basado en el user con su content-item
    public function showForUser($slug, $userId)
    {

        $query =  Tag::query();

        $query->where('slug', $slug)
            ->whereHas('contentItems', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });

        return $query->firstOrFail();
    }

    public function showTagWithContentItem($slug, $userId, ?int $perPage = null)
    {
        $contentItemsQuery = ContentItem::query();
        $contentItemsQuery->whereHas('tags', function ($query) use ($slug) {
            $query->where('slug', $slug);
        });
        $contentItemsQuery->where('user_id', $userId);

        if ($perPage) {
            return $contentItemsQuery->paginate($perPage);
        }
        return $contentItemsQuery->paginate(10);
    }


    public function show($slug)
    {
        return Tag::where('slug', $slug)->firstOrFail();
    }



    public function store(array $data)
    {
        return Tag::create($data);
    }

    public function update(array $data, string $id)
    {
        $tag = Tag::where('status', $this->active)->where('id', $id)->firstOrFail();
        $tag->update($data);
        return $tag->fresh();
    }

    public function destroy($id)
    {
        $tag = Tag::where('status', $this->active)->where('id', $id)->firstOrFail();
        $tag->update(['status' => !$this->active]);

        ContentTag::where('tag_id', $tag->id)
            ->update(['status' => !$this->active]);

        return $tag;
    }
}