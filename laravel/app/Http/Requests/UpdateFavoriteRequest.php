<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Favorites typically don't need update authorization
    }

    public function rules(): array
    {
        return [
            // Favorites don't have updatable fields
            // This request exists for consistency but typically won't be used
        ];
    }
}
