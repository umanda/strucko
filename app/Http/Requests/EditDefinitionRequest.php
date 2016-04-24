<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Definition;

class EditDefinitionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch ($this->method()) {
            case 'POST':
            {
                // This part is validated using middleware.
                return true;
            }
            case 'GET':
            case 'PUT':
            case 'PATCH':
            {
                // If admin, let through.
                if ($this->user()->role_id == 1000) {
                    return true;
                }
                // Get the definition id from the route.
                $definitionId = $this->route('id');

                // Check if the current user is the owner of the definition.
                $isOwner = Definition::where('id', $definitionId)
                        ->where('user_id', $this->user()->id)
                        ->exists();

                // If user is not owner, do not let through.
                if ( ! $isOwner) {
                    return false;
                }
                // User is the owner, let him through.
                return true;
            }
            case 'DELETE':
            {
                // If admin, let through.
                if ($this->user()->role_id == 1000) {
                    return true;
                }
            }
            default:
            {
                return false;
            }
        }
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method())
        {
            case 'POST':
            case 'PUT':
            case 'PATCH':
            {
                // Remove spaces from definition.
                $input = $this->all();
                $input['definition'] = trim($input['definition']);
                $input['source'] = trim($input['source']);
                $this->replace($input);

                return [
                    'definition' => 'required',
                    'concept_id' => 'required|integer|min:1',
                    'term_id' => 'required|integer|min:1',
                    'language_id' => 'required|alpha|min:1|max:3',
                    'link' => 'url',
                ]; 
            }
            default:
            {
                return [
            
                ];
            }
        }
    }
}
