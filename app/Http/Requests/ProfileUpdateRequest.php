<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // 'name' => ['string', 'max:255']: The name field should be a string, 
        // and its maximum length should be 255 characters.
        // 'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)]: 
        // The email field should be a valid email format and should have a maximum length of 255 characters. 
        // Additionally, it checks the uniqueness of the email in the users table. 
        // The Rule::unique method checks for uniqueness based on the users table and uses 
        // ignore($this->user()->id) to ignore the current user's email when performing the uniqueness check. 
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}
