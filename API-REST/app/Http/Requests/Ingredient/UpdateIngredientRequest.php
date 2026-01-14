<?php

namespace App\Http\Requests\Ingredient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',              
                Rule::unique('ingredients', 'name')->ignore($this->route('id')),
            ],
        ];      
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The ingredient name is required.',
            'name.string' => 'The ingredient name must be a valid text.',
            'name.max' => 'The ingredient name must not exceed 100 characters.',
            'name.regex' => 'The ingredient name can only contain letters and spaces.',
            'name.unique' => 'This ingredient name already exists.',
        ];
    }
}