<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:120'],
            'phone'        => ['required', 'string', 'max:40'],
            'email'        => ['nullable', 'email', 'max:191'],
            'service_type' => ['nullable', 'string', 'max:120'],
            'suburb'       => ['nullable', 'string', 'max:120'],
            'message'      => ['required', 'string', 'min:5', 'max:4000'],
            'source'       => ['nullable', 'string', 'max:60'],
            // Optional job photos — homeowners often prefer to snap a photo.
            'photos'       => ['nullable', 'array', 'max:5'],
            'photos.*'     => ['image', 'mimes:jpeg,jpg,png,webp,heic', 'max:8192'],
            // Honeypot field — must be empty
            'website'      => ['nullable', 'size:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.size'  => 'Spam detected.',
            'photos.max'    => 'You can attach up to 5 photos.',
            'photos.*.image' => 'Each attachment must be an image.',
            'photos.*.max'  => 'Each photo must be 8MB or smaller.',
        ];
    }

    public function attributes(): array
    {
        return [
            'photos.*' => 'photo',
        ];
    }
}
