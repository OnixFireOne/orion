<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
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
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'userId' => ['required'],
                'postId' => ['required'],
                'content' => ['required']
            ];
        } else {
            return [
                'userId' => ['sometimes', 'required'],
                'postId' => ['sometimes', 'required'],
                'content' => ['sometimes', 'required']
            ];
        }
    }

    protected function prepareForValidation(): void
    {
        if ($this->userId || $this->postId) {
            $this->merge([
                'user_id' => $this->userId,
                'post_id' => $this->postId
            ]);
        }
    }
}
