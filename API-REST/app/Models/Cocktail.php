<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cocktail extends Model
{
    public function ingredients()
    {
        return $this->belongsToMany(\App\Models\Ingredient::class, 'cocktail_ingredient');
    }
}
