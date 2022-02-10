<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{   protected $errorBag = 'contactForm';
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
            'contactname' => 'required|max:255',

        'user-email' => 'required|max:50',
        'formmessage'=>'required|string|max:255',
        'g-recaptcha-response'=>'required'
        ];
    }

    public function messages(){
        return [
            'contactname.required'=>'Name Required',
            'user-email.required' => 'Email Required',
            'formmessage.required' => 'Message Required',
            'g-recaptcha-response.required' => 'Captcha Required',
    ];
    }
}
