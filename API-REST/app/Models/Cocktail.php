<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cocktail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'elaboration_method',
        'user_id',
    ];

    public function ingredients()
    {
        return $this->belongsToMany(\App\Models\Ingredient::class, 'cocktail_ingredient')
                    ->withPivot('amount', 'unit');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'cocktail_user_favorites')
            ->withTimestamps();
    }

}
