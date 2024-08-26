<?php

namespace App\Services;

use App\Repositories\BicycleRepositoryInterface;

class BicycleService
{
    public function __construct(
        protected BicycleRepositoryInterface $bicycleRepository
    ) {
    }

    public function index()
    {
        return $this->bicycleRepository->index();
    }

    public function store(array $data)
    {
        return $this->bicycleRepository->store($data);
    }

    public function show($id)
    {
        return $this->bicycleRepository->show($id);
    }

    public function update(array $data, $id)
    {
        return $this->bicycleRepository->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->bicycleRepository->destroy($id);
    }
}
