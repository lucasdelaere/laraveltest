<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => "required|string|between:2,255",
            "email" => "required|email|unique:users|max:255",
            "roles" => "required",
            "password" => ["required", Password::min(6)],
            "photo_id" => [
                "required",
                File::types(["png", "jpg", "webp", "jpeg"])
                    ->min(1)
                    ->max(2 * 1024),
            ],
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "Name is required",
            "name.string" => "Name must be string",
            "name.between" => "Name must be between 2 and 255",
            "email.required" => "This email is required",
            "password.required" => "This password is required",
        ];
    }
}
