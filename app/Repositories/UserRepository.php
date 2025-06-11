<?php

namespace App\Repositories;

use App\Models\User;
use App\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function allBy($filter)
    {
        // return User::where('name', 'like', "%$filter%")->get();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
    public function withTrashed()
    {
        return User::withTrashed();
    }

}
