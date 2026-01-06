<?php

namespace App\Policies;

use App\Models\Cocktail;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CocktailPolicy
{
    public function viewAny(?User $user): bool
    {
        return true; 
    }

    public function view(?User $user, Cocktail $cocktail): bool
    {
        return true; 
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'verified']);
    }

    public function update(User $user, Cocktail $cocktail): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('verified')
            && $user->id === $cocktail->user_id;
    }

    public function delete(User $user, Cocktail $cocktail): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('verified')
            && $user->id === $cocktail->user_id;
    }
}
