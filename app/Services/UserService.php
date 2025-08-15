<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Get all User records.
     */
    public function getAll(): Collection
    {
        return User::all();
    }

    /**
     * Find a User by ID.
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Create a new User.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update a User.
     */
    public function update(int $id, array $data): bool
    {
        $User = User::findOrFail($id);
        return $User->update($data);
    }

    /**
     * Delete a User.
     */
    public function delete(int $id): bool
    {
        $User = User::findOrFail($id);
        return $User->delete();
    }

    /**
     * Get User records with pagination.
     */
    public function getPaginated(int $perPage = 15)
    {
        return User::paginate($perPage);
    }

    /**
     * Search User records.
     */
    public function search(string $query): Collection
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();
    }
} 