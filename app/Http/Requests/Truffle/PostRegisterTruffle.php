<?php

namespace App\Http\Requests\Truffle;

use App\Validation\CreateTruffle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostRegisterTruffle extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return Auth::hasUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return CreateTruffle::getRules();
    }
}
