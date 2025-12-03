<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users can checkout
        return $this->user() !== null;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method' => 'required|in:credit_card,mobile_money,cash_on_delivery',
             'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_region' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'shipping_digital_address' => 'nullable|string|max:50',
            'shipping_address.country' => 'required|string|max:50',

             // billing address fields
            'billing_address' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:100',
            'billing_region' => 'nullable|string|max:100',
            'billing_phone' => 'nullable|string|max:20',
        ];
    }
}
