<?php

namespace App\Policies;

use App\Models\Ingredient;
use App\Models\User;

class IngredientPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Ingredient $ingredient): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'verified']);
    }

    public function update(User $user, Ingredient $ingredient): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if (!$user->hasRole('verified')) {
            return false;
        }

        if ($ingredient->user_id !== $user->id) {
            return false;
        }

        $usedByOther = $ingredient->cocktails()
            ->where('user_id', '!=', $user->id)
            ->exists();

        return !$usedByOther;
    }

    public function delete(User $user, Ingredient $ingredient): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if (!$user->hasRole('verified')) {
            return false;
        }

        if ($ingredient->user_id !== $user->id) {
            return false;
        }

        $usedByOther = $ingredient->cocktails()
            ->where('user_id', '!=', $user->id)
            ->exists();

        return !$usedByOther;
    }
}
