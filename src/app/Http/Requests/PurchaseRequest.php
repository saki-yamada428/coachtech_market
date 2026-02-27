<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_method' => 'required',
            // 'postal_code' => 'required|integer',
            // 'address' => 'required|string',
            // 'building' => 'nullable|string',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'postal_code.regex' => '郵便番号はハイフンありで記入してください。'
    //         'picture.mimes' => 'プロフィール画像はjpegかpng形式にしてください。'
    //     ];
    // }
}
