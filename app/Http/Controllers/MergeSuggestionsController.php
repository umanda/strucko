<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MergeSuggestionsController extends Controller
{
    // After the merge, reset votes_sum on the merged term.
    // Merge all terms with the same concept_id.
    // Merge all definitions with the same concept_id to the new concept_id.
}
