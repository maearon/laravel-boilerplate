<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRelationshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'followed_id' => ['required', 'integer', 'exists:users,id', 'not_in:' . Auth::id()],
        ];
    }

    public function messages(): array
    {
        return [
            'followed_id.required' => 'The user to follow is required.',
            'followed_id.exists' => 'The selected user does not exist.',
            'followed_id.not_in' => 'You cannot follow yourself.',
        ];
    }
}
