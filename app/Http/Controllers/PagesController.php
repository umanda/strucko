<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use DB;
use Auth;

class PagesController extends Controller
{
    public function __construct()
    {
        // Check if user has Administrator role for specified methods.
        $this->middleware('auth', ['only' => ['getTest']]);
        $this->middleware('role:1000', ['only' => ['getTest']]);
    }
    
    public function getHome()
    {
        $categoryFilters = [];
        Auth::check() ? '' : $categoryFilters['status_id'] = 1000;
        
        $categories = Term::where($categoryFilters)
                ->select(DB::raw('count(*) as count'), 'language_id', 'scientific_field_id')
                ->groupBy('language_id', 'scientific_field_id')
                ->orderBy('count', 'DESC')
                ->take(8)
                ->with('language', 'scientificField')
                ->get();
                
        return view('pages.home', compact('categories'));
    }
    
    public function getPrivacyPolicy()
    {                
        return view('pages.privacy_policy');
    }
    
    public function getCookiePolicy()
    {                
        return view('pages.cookie_policy');
    }
    
    public function getDisclaimer()
    {                
        return view('pages.disclaimer');
    }
    
    public function getAbout()
    {                
        return view('pages.about');
    }
    
    public function getTermsOfUse()
    {                
        return view('pages.terms_of_use');
    }
    
    public function getTest(Request $request)
    {
        DB::connection()->enableQueryLog();
        $total = DB::table('terms')->select('terms.user_id', DB::raw('COUNT(term) + def_sum as total')  )
                ->leftJoin(DB::raw('(SELECT def.user_id, COUNT(def.definition) as def_sum'
                    . ' FROM definitions AS def'
                    . ' WHERE def.user_id = ? AND def.status_id < 1000'
                    . ' GROUP BY def.user_id) as def'), 'terms.user_id', '=', 'def.user_id')
                ->setBindings([3])
                ->where('terms.status_id','<', 1000)
                ->where('terms.user_id', 3)
                ->groupBy('terms.user_id')
                ->value('total');
        $query = DB::getQueryLog();
        DB::connection()->disableQueryLog();
        //var_dump($query);
       var_dump((int)$total < 10);
    }
}
