<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
Use App\User;
Use DB;
Use App\Term;
Use App\Definition;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:1000', ['only' => [
            'getIndex',
            ]]);
    }
    
    public function getIndex(Request $request)
    {
        $users = User::latest()
                ->with('role')
                ->get();
        
        return view('users.index', compact('users'));
    }
    
    /**
     * Display a settings page for the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStats(Request $request)
    {
        $stats = [];
        $stats['terms']['approved'] = Term::approved()->where('user_id', $request->user()->id)->count();
        $stats['terms']['suggested'] = Term::suggested()->where('user_id', $request->user()->id)->count();
        $stats['terms']['rejected'] = Term::rejected()->where('user_id', $request->user()->id)->count();
        
        $stats['definitions']['approved'] = Definition::approved()->where('user_id', $request->user()->id)->count();
        $stats['definitions']['suggested'] = Definition::suggested()->where('user_id', $request->user()->id)->count();
        $stats['definitions']['rejected'] = Definition::rejected()->where('user_id', $request->user()->id)->count();
        
        return view('users.stats', compact('stats'));
    }

}
