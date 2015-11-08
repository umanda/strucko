<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class EditTermRequest extends Request
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
     * 
     * @return array
     */
    public function rules()
    {
        // First make sure that the term input has no spaces
        $input = $this->all();
        $input['term'] = trim($input['term']);
        $this->replace($input);
        
        return [
            'term' => 'required|min:2|max:255',
            'language_id' => 'required|alpha|min:1|max:3',
            'part_of_speech_id' => 'required|integer|min:1',
            'scientific_field_id' => 'required|integer|min:1',
            'is_abbreviation' => 'boolean'
        ];
    }
}
