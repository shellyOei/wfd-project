<?php

namespace App\Repositories\Contracts;

use App\Models\User; 
use App\Repositories\Contracts\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $data): User; 
    /**
     * Find a user by their email address.
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email);

    /**
     * Get all active users.
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function getActiveUsers();

    // Overriding return type for find/create/update if desired (via PhpDoc)
    // This is where PHP's lack of generics hits. You'd typically rely on
    // the concrete implementation to return the specific model.
    // For example, in the concrete User Repo, find($id) would return User.
    /**
     * Find a user by their ID.
     * @param int $id
     * @return User|null
     */
    // public function find($id); // No need to redefine, but can add PhpDoc for clarity.
}