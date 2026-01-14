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

    public function messages(): array
    {
        return [
            'name.required' => 'The cocktail name is required.',
            'name.string' => 'The cocktail name must be a valid text.',
            'name.max' => 'The cocktail name must not exceed 100 characters.',
            'name.unique' => 'A cocktail with this name already exists.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a valid text.',
            'elaboration_method.required' => 'The elaboration method is required.',
            'elaboration_method.string' => 'The elaboration method must be a valid text.',
            'ingredients.required' => 'At least one ingredient is required.',
            'ingredients.array' => 'The ingredients must be provided as a list.',
            'ingredients.min' => 'At least one ingredient is required.',
            'ingredients.*.ingredient_id.required' => 'Each ingredient must have an ID.',
            'ingredients.*.ingredient_id.integer' => 'The ingredient ID must be a number.',
            'ingredients.*.ingredient_id.exists' => 'One or more ingredients do not exist.',
            'ingredients.*.amount.required' => 'The amount is required for each ingredient.',
            'ingredients.*.amount.numeric' => 'The amount must be a number.',
            'ingredients.*.amount.min' => 'The amount must be greater than or equal to 0.',
            'ingredients.*.unit.required' => 'The unit is required for each ingredient.',
            'ingredients.*.unit.in' => 'The unit must be one of: cl, ml, oz, dash, units, tablespoons.',
        ];
    }
}