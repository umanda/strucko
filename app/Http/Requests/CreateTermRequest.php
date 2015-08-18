<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateTermRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // User has to be loged in.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     * TODO: check validation rules: language should be 3 chars
     * 
     * @return array
     */
    public function rules()
    {
        return [
            'term' => 'required|min:2|max:255',
            'language_id' => 'required|alpha|min:1|max:3',
            'part_of_speech_id' => 'required|integer|min:1',
            'scientific_field_id' => 'required|integer|min:1',
            
        ];
    }
}
