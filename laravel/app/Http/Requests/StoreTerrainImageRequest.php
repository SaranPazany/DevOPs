<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTerrainImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'terrain_id' => ['required', 'exists:terrains,id'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'max:2048'],
        ];
    }
}
