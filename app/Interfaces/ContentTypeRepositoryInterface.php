<?php

namespace App\Interfaces;

interface ContentTypeRepositoryInterface
{
    //
    public function index(?array $with = [], ?array $filters = [], ?int $perPage = null);
    public function indexForUser();
    public function getById(string|int $id);
    public function store(array $data);
    public function update(array $data, string|int $id);
    public function delete(string|int $id);
}
