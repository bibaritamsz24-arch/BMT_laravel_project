<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentPortalPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('student') !== null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password:student'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.current_password' => 'Wrong password.',
            'password.confirmed' => 'Password not match.',
            'password.different' => 'The new password must be different from the current password.',
        ];
    }
}
