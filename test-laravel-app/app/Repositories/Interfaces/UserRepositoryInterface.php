<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Find a model by its primary key.
     */
    public function find(int $id): ?User;

    /**
     * Find a model by its primary key or throw an exception.
     */
    public function findOrFail(int $id): User;

    /**
     * Get all models.
     */
    public function all(): Collection;

    /**
     * Create a new model.
     */
    public function create(array $data): User;

    /**
     * Update a model.
     */
    public function update(User $model, array $data): bool;

    /**
     * Delete a model.
     */
    public function delete(User $model): bool;

    /**
     * Get models by criteria.
     */
    public function findBy(array $criteria): Collection;

    /**
     * Get a single model by criteria.
     */
    public function findOneBy(array $criteria): ?User;
} 