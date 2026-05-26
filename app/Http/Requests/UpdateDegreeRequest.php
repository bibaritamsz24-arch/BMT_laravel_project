<?php

namespace App\Http\Requests;

use App\Models\Degree;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDegreeRequest extends FormRequest
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
        /** @var Degree $degree */
        $degree = $this->route('degree');

        return [
            'title' => [
                'required',
                'string',
                'min:4',
                'max:255',
                Rule::unique('degrees', 'title')->ignore($degree->id),
            ],
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
