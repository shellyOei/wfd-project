<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\BaseRepositoryInterface; 
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface // Implementasi interface spesifik
{
    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::find($id); 
    }

    public function findOrFail($id)
    {
        return User::findOrFail($id); 
    }

    public function create(array $data): User 
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return User::create($data);
    }

    public function update($id, array $data): User 
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

    // implentation of user-specific methods examples
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function getActiveUsers()
    {
        return User::where('is_active', true)->get();
    }
}