<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class GroupUpdateRequest extends FormRequest
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
        $userId = auth()->user()->id;
        return [
            'name' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('groups', 'name')->where('user_id', $userId)->ignore($this->route('group')),
            ],
            'description' => ['sometimes', 'nullable', 'max:200'],
        ];
    }

    public function failedValidation(Validator $validator): void {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ], Response::HTTP_FORBIDDEN));
    }
}
