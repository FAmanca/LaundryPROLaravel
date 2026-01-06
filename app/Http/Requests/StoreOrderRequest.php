<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,customer_id',
            'parfume_id' => 'required|exists:parfumes,parfume_id',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer,qris',
            'payment-status' => 'required|in:paid,unpaid,downpayment',
            'downpayment_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'total_amount' => 'required|numeric|min:0',
        ];
    }
}
