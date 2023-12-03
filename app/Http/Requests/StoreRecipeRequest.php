<?php

namespace App\Http\Requests;

use App\Models\Ingredient;
use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:255'],
            'ingredients' => ['required', 'array'],
            'ingredients.*.id' => ['required', 'exists:ingredients,id'],
            'ingredients.*.amount' => ['required', 'numeric', 'min:0', 
                function($attribute, $value, $fail) {
                    // $attribute = "ingredients.0.amount"
                    $ingredientId = $this->input('ingredients.' . explode('.', $attribute)[1] . ".id");
                    $ingredient = Ingredient::find($ingredientId);

                    if($ingredient && $ingredient->measure === 'pieces' && is_float($value)){
                        $fail('The amount cannot contain fractions when the ingredient measure is "pieces"');
                    }
                }
            ]
        ];
    }
}
