<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'total_price' => 'required|integer',
            'payment_method' => 'nullable|string',
            'payment_type' => 'required|in:cash,online',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|integer',
        ];

        // If payment type is cash, require paid_amount and change
        if ($this->input('payment_type') === 'cash') {
            $rules['paid_amount'] = 'required|integer';
            $rules['change'] = 'required|integer';
        }

        // If payment type is online, require tripay fields
        if ($this->input('payment_type') === 'online') {
            $rules['tripay_method'] = 'required|string';
            $rules['customer_phone'] = 'nullable|string';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'payment_type.required' => 'Payment type is required',
            'payment_type.in' => 'Payment type must be cash or online',
            'tripay_method.required' => 'Payment method is required for online payment',
        ];
    }
}
