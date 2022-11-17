<?php

namespace App\Http\Requests;

use App\Traits\ResponseWithHttpRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
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
            'email'         => 'required|exists:users,email',
            'password'      => 'required',
        ];
    }

    function messages()
    {
        return   [
            'email.required'         => 'Email address should be required',
            'email.exists'           => 'We could not find an account with this email address',
            'password.required'      => 'Password should be required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->sendFailed($validator->errors()->first(), 200)
           );
    }
}
