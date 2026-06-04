<?php

namespace App\Http\Requests;

use App\Models\Tool;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthStoreToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', Tool::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'link' => ['required', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['required', 'string', 'exists:tags,name'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()?->id) {
            $this->merge(['user_id' => $this->user()->id]);
        }
    }
}
