<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTerrainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->terrain->owner_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['sometimes', 'string', 'max:255'],
            'area_size' => ['sometimes', 'numeric', 'min:0'],
            'price_per_day' => ['sometimes', 'numeric', 'min:0'],
            'available_from' => ['nullable', 'date', 'after_or_equal:today'],
            'available_to' => ['nullable', 'date', 'after:available_from'],
            'is_available' => ['boolean'],
            'main_image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
