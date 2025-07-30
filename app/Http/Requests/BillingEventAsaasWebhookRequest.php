<?php

namespace App\Http\Requests;

use App\Models\FinancialMovement;
use Illuminate\Foundation\Http\FormRequest;

class BillingEventAsaasWebhookRequest extends FormRequest
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
            'event' => 'required|string|in:PAYMENT_CONFIRMED,PAYMENT_RECEIVED',
            'payment' => 'required|array',
            'payment.id' => 'required|string',
            'payment.value' => 'required|numeric',
        ];
    }
}
