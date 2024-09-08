<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->tokenCan('server:update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'userId' => ['required'],
                'title' => ['required'],
                'content' => ['required']
            ];
        } else {
            return [
                'userId' => ['sometimes', 'required'],
                'title' => ['sometimes', 'required'],
                'content' => ['sometimes', 'required']
            ];
        }
    }

    protected function prepareForValidation(): void
    {
        if ($this->userId) {
            $this->merge([
                'user_id' => $this->userId
            ]);
        }
    }
}
