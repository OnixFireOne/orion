<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'postId' => ['required', 'integer'],
            'userId' => ['required', 'integer'],
            'content' => ['required']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'post_id' => $this->postId,
            'user_id' => $this->userId
        ]);
    }
}
