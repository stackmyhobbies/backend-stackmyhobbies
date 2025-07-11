<?php

namespace App\Interfaces;

interface TagRepositoryInterface
{
    //
    public function index();
    public function show($slug);
    public function store(array $data);
    public function update(array $data, string $id);
    public function destroy($id);
}
