<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Ingredient::class => \App\Policies\IngredientPolicy::class,
        \App\Models\Cocktail::class => \App\Policies\CocktailPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Passport::enablePasswordGrant();
    }
}
