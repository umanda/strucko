<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;

class SitemapsController extends Controller
{
    public function index()
    {
        // You can use the route helpers too.
        //Sitemap::addSitemap(route('sitemaps.terms1'));

        // Return the sitemap to the client.
        //return Sitemap::index();
    }
    
    public function terms1()
    {   $arr = [];
        Term::chunk(2, function ($terms) use (&$arr) {
                foreach ($terms as $term) {
                    $arr[] = $term;
                }
            });
        dd($arr);
    }
}
