<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{

    public function index();
    public function show($id);
    public function store(array $array);
    public function update(array $array, $id);
    public function destroy($id);
}
