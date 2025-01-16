<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMeterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mpxn' => ['required', Rule::unique('meters')],
            'type' => ['required', Rule::in(['electric', 'gas'])],
            'installation_date' => ['required'],
            'estimated_annual_consumption' => ['required', 'integer', 'min:2000', 'max:8000'],
        ];
    }
}
