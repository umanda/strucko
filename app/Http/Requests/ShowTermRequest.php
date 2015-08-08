<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Term;
use Illuminate\Support\Facades\Auth;

class ShowTermRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Get the term.
        $term = $this->route('terms');
        // Check if it is approved.
        $isApproved = Term::where('id', $term->id)
                ->where('status_id', 1000)
                ->exists();
        
        // If the term is not approved, the user has to be signed in.
        if ( ! $isApproved) {
            return Auth::check();
        }
        // The term is approved so anyone can see it.
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
            //
        ];
    }
}
