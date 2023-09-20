<?php

namespace App\Http\Requests;

use App\Models\Birthday;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class BirthdayStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $birthday = Birthday::find($this->route('birthday'));

        if (!$birthday) {
            return true;
        }

        return $this->user()->can('update', $birthday);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 
                'max:255'
            ],
            'title' => [
                'required', 
                'max:255'
            ],
            'body' => [
                'nullable', 
                'max:2000',
            ],
            'phone_number' => [
                'nullable',
                'numeric',
            ],
            'birthday_date' => [
                'required',
                'date',
            ],
            'group_id' => 'nullable',
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
