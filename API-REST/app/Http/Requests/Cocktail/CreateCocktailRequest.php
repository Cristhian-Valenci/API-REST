<?php

namespace App\Http\Requests\Cocktail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCocktailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // En create siempre permitimos que un usuario autenticado cree un cocktail
        return auth()->check();
    }

    /**
     * Prepara los datos antes de la validación.
     */
    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => strtolower(trim($this->name)),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:cocktails,name', 
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
                }
            ],
            'ingredients.*.ingredient_id' => 'required|integer|exists:ingredients,id',
            'ingredients.*.amount' => 'required|numeric|min:0',
            'ingredients.*.unit' => 'required|in:cl,ml,oz,dash,units,spoon',
        ];
    }
}
