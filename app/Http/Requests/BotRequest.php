<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class BotRequest extends FormRequest
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
            'bot_name'      => 'required',
            'exchange'      => 'required|string|exists:exchanges,uuid',
            'market'        => 'required|string|exists:markets,uuid',
            'trade_type'    => ['required', 'string', Rule::in(['spot', 'future'])],
            'stop_loss'     => 'nullable|numeric|required_if:trade_type,future',
            'take_profit'   => 'required|numeric',
            'capital'       => 'required|numeric',
            'first_buy'     => 'required|numeric',
            'margin_limit'  => 'required|numeric',
            'm_ratio'       => 'required',
            'price_drop'    => 'required',
            'strategy_mode' => ['nullable', 'required_if:trade_type,future', Rule::in(['short', 'long'])],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
