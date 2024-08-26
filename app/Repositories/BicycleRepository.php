<?php

namespace App\Repositories;

use App\Models\Bicycle;

class BicycleRepository implements BicycleRepositoryInterface
{
    public function index()
    {
        return Bicycle::all();
    }

    public function store(array $data)
    {
        return Bicycle::create($data);
    }

    public function show($id)
    {
        return Bicycle::findOrFail($id);
    }

    public function update(array $data, $id)
    {
        $bicycle = Bicycle::findOrFail($id);
        $bicycle->update($data);
        return $bicycle;
    }

    public function destroy($id)
    {
        $bicycle = Bicycle::findOrFail($id);
        $bicycle->delete();
    }
}
