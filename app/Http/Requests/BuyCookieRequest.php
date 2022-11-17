<?php

namespace App\Http\Requests;

use App\Traits\ResponseWithHttpRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BuyCookieRequest extends FormRequest
{
    use ResponseWithHttpRequest;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'quantity'         => 'required|integer|between:1,1000',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->sendFailed($validator->errors()->first(), 200)
           );
    }
}
