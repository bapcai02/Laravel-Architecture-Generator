<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Find a model by its primary key.
     */
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find a model by its primary key or throw an exception.
     */
    public function findOrFail(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Get all models.
     */
    public function all(): Collection
    {
        return User::all();
    }

    /**
     * Create a new model.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update a model.
     */
    public function update(User $model, array $data): bool
    {
        return $model->update($data);
    }

    /**
     * Delete a model.
     */
    public function delete(User $model): bool
    {
        return $model->delete();
    }

    /**
     * Get models by criteria.
     */
    public function findBy(array $criteria): Collection
    {
        $query = User::query();
        
        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->get();
    }

    /**
     * Get a single model by criteria.
     */
    public function findOneBy(array $criteria): ?User
    {
        $query = User::query();
        
        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->first();
    }
} 