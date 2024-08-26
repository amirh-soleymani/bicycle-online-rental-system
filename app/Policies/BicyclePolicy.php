<?php

namespace App\Policies;

use App\Models\User;

class BicyclePolicy
{
    public function index(User $user)
    {
        return $user->type == 'admin';
    }

    public function store(User $user)
    {
        return $user->type == 'admin';
    }

    public function show(User $user)
    {
        return $user->type == 'admin';
    }

    public function update(User $user)
    {
        return $user->type == 'admin';
    }

    public function destroy(User $user)
{
    return $user->type == 'admin';
}

}
