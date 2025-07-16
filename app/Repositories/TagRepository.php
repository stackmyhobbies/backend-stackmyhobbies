<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Models\ContentTag;
use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{


    private $active = true;

    public function index()
    {
        return Tag::all();
    }

    public function show($slug)
    {
        return Tag::where('status', $this->active)->where('slug', $slug)->firstOrFail();
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
