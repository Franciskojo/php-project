<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
 {
    return true;
 }

    public function rules()
 {
    return [
        'name'        => ['sometimes', 'string', 'max:255'],
        'description' => ['sometimes', 'string'],
        'price'       => ['sometimes', 'numeric', 'min:0'],
        'stock'       => ['sometimes', 'integer', 'min:0'],
        'image'       => ['nullable', 'image', 'max:2048'],
    ];
 }
}
