<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditTranslationRequest extends Request
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
            'term' => 'required|min:2|max:255',
            'language_id' => 'required|alpha|min:1|max:3',
            'part_of_speech_id' => 'required|integer|min:1',
            'scientific_field_id' => 'required|integer|min:1', 
            'synonym_id' => 'required|integer|min:1',
        ];
    }
}
