<?php

namespace App\Interfaces;

interface ProgressStatusRepositoryInterface
{
    public function index(?array $with = [], ?array $filters = [], ?int $perPage = null);
    public function indexForUser();
    public function show($id);
    public function store(array $data);
    public function update(array $data, $id);
    public function destroy($id);
}
