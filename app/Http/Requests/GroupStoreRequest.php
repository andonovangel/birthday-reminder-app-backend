<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class GroupStoreRequest extends FormRequest
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
                'required',
                'string',
                'max:255',
                Rule::unique('groups')->where('user_id', $userId),
            ],
            'description' => ['nullable', 'max:2000'],
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
