<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTerrainRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'area_size' => ['required', 'numeric', 'min:0'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'available_from' => ['nullable', 'date', 'after_or_equal:today'],
            'available_to' => ['nullable', 'date', 'after:available_from'],
            'is_available' => ['boolean'],
            'main_image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
