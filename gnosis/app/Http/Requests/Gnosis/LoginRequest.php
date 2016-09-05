<?php

namespace App\Http\Requests\Gnosis;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Session;

class LoginRequest extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'email'            => 'required|email',
            'password'         => 'required',
        ];
    }

    /**
     * Hijacks the request and returns a flash message
     *
     * @param  Validator $validator [An instance of the Validator class]
     * @return Validator            [An instance of the Validator class]
     */
    protected function failedValidation(Validator $validator)
    {
        Session::flash('flash_message', [
            'type'    => 'danger',
            'message' => 'There was a problem logging in'
        ]);
        return parent::failedValidation($validator);
    }
}
