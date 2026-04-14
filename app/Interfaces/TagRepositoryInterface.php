<?php

namespace App\Interfaces;

interface TagRepositoryInterface
{
    //TODO MIRAR ELSHOW FOR USER Y SHOW FOR ADMIN
    public function indexForUser();
    public function index(?array $with = [], ?array $filters = [], ?int $perPage = null);
    public function show($slug);
    public function showForUser($slug, $userId);
    public function showTagWithContentItem($slug, $userId, ?int $perPage = null);
    public function store(array $data);
    public function update(array $data, string $id);
    public function destroy($id);
}