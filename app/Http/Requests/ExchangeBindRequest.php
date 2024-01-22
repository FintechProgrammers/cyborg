<?php

namespace App\Http\Requests;

use App\Models\Exchange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ExchangeBindRequest extends FormRequest
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
        $rules = [
            'exchange'  => 'required|exists:exchanges,uuid',
            'api_key'   => 'required',
            'secret'    => 'required',
            'password'  => 'nullable'
        ];

        // If exchange is kucoin, make password required
        $exchange = Exchange::whereUuid($this->input('exchange'))->first();

        if (!$exchange) {
           return throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Invalid exchange',
                'errors' => [],
            ], 400));
        }

        if (in_array($exchange->slug, ['kucoin'])) {
            $rules['password'] = 'required';
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
