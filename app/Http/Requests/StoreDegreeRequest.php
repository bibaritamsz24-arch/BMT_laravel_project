<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDegreeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim((string) $this->input('title')),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:4', 'max:255', 'unique:degrees,title'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'This degree title already exists.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'degree title',
        ];
    }
}
