<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditLanguageRequest extends Request
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
            'ref_name' => 'required|max:255',
            'id' => 'required|min:3|max:3',
            'scope' => 'required|in:I,M,S',
            'type' => 'required|in:A,C,E,H,L,S',
        ];
    }
}
