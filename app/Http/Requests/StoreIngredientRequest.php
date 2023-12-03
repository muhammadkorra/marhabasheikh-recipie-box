<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIngredientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'measure' => ['required', Rule::in(['kg', 'g', 'pieces'])],
            'supplier' => ['required', 'exists:suppliers,id']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'supplier_id' => $this->supplier
        ]);
    }
}
