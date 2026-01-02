<?php

namespace App\Http\Requests\Cocktail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCocktailRequest extends FormRequest
{
    public function authorize(): bool
    {
        
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => strtolower(trim($this->name)),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cocktails', 'name')->ignore($this->cocktail),
            ],
            'description' => 'required|string',
            'elaboration_method' => 'required|string',
            'ingredients' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $ids = array_column($value, 'ingredient_id');
                    if (count($ids) !== count(array_unique($ids))) {
                        $fail('You cannot repeat the same ingredient.');
                    }
                },
            ],
            'ingredients.*.ingredient_id' => 'required|integer|exists:ingredients,id',
            'ingredients.*.amount' => 'required|numeric|min:0',
            'ingredients.*.unit' => 'required|in:cl,ml,oz,dash,units,tablespoons',
        ];
    }
}
