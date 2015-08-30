<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Filter;

class MenuLettersRepository
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function get() {
        
    }
}

