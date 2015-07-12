<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Term;

class TermsController extends Controller
{
    public function index()
    {
        return Term::all();
    }
}
