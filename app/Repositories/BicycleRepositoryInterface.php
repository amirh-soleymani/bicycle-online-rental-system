<?php

namespace App\Repositories;

interface BicycleRepositoryInterface
{
    public function index();

    public function store(array $data);

    public function show($id);

    public function update(array $data, $id);

    public function destroy($id);
}
