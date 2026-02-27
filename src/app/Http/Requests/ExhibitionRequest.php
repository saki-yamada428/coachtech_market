<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'picture'       => 'required|image|mimes:jpeg,png',
            'price'         => 'required|numeric|min:1',
            'description'   => 'required|string|max:255',
            'condition_id'  => 'required',
            'category_id'   => 'required|array',
            'category_id.*' => 'integer|exists:categories,id',
        ];
    }
}
