<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

// バリデーション
use App\Http\Requests\RegisterRequest;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // $messages = [
        //     'name.required' => 'お名前を入力してください',
        //     'name.max' => 'お名前は20文字以内で入力してください',
        // ];

        // Validator::make($input, [
        //     'name' => ['required', 'string', 'max:20'],
        //     'email' => ['required','string','email','max:255',Rule::unique(User::class)],
        //     'password' => $this->passwordRules(),
        // ], $messages)->validate();

        // FormRequest を手動で実行
        app(RegisterRequest::class)->validateResolved();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

    }
}
