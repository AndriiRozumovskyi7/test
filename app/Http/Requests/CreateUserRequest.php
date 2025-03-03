<?php

namespace App\Http\Requests;

use App\Rules\ImageSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'name' => ['required', 'min:2', 'max:60'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'regex:/^\+380\d{9}$/'],
            'position_id' => 'required|integer',
            'photo' => ['required', 'file', 'mimes:jpg,jpeg', 'image', 'max:5120', new ImageSize]
        ];
    }
}
