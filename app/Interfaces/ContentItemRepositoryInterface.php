<?php

namespace App\Interfaces;

interface ContentItemRepositoryInterface
{
    public function index(array $with = [], array $filters = [], ?int $perPage = null);
    public function show($slug);
    public function store(array $data);
    public function update(array $data, $id);
    public function destroy($id);
}
