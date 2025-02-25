<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'link' => 'required|url'
        ];
    }
}
