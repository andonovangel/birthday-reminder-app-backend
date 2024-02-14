<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BirthdayListRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sortBy' => Rule::in(['title', 'birthday_date']),
            'sortOrder' => Rule::in(['asc', 'desc']),
            'date' => 'date_format:Y-m-d',
        ];
    }

    public function messages(): array
    {
        return [
            'sortBy' => "The 'sortBy' parameter accepts only 'title' or 'birthday_date' value",
            'sortOrder' => "The 'sortOrder' parameter accepts only 'asc' or 'desc' value",
        ];
    }
}
