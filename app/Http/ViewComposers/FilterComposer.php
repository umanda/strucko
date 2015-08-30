<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Repositories\FilterRepository;

/**
 * Description of FilterComposer
 *
 * @author mivancic
 */
class FilterComposer
{
    protected $filters;
    
    public function __construct(FilterRepository $filters)
    {
        $this->filters = $filters;
    }
    
    public function compose(View $view)
    {
        $view->with('filters', $this->filters->all());
    }
}
