<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class BicycleRentRequest extends FormRequest
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
            'bicycle_id' => 'required|exists:bicycles,id',
            'pickup_date' => 'required|date|after:yesterday',
            'return_date' => 'required|date|after:pickup_date'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            Response::errorResponse('Validation Error!', $validator->errors(), 400)
        );
    }
}
