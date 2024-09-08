<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        $user = $this->user();

        return $user !== null && $user->tokenCan('server:create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userId' => ['required','integer'],
            'title' => ['required'],
            'content' => ['required']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->userId
        ]);
    }

}
