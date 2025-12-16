<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'user_id'];

    public function cocktails()
    {
        return $this->belongsToMany(\App\Models\Cocktail::class, 'cocktail_ingredient');
    }

}
