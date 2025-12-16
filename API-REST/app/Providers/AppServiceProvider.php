<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Ingredient::class => \App\Policies\IngredientPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Passport::enablePasswordGrant();
       Passport::ignoreRoutes();
    }

     
}
