<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Repositories\TermsFilterRepository;

/**
 * Description of FilterComposer
 *
 * @author mivancic
 */
class TermsFilterComposer
{
    protected $filters;
    
    public function __construct(TermsFilterRepository $filters)
    {
        $this->filters = $filters;
    }
    
    public function compose(View $view)
    {
        $view->with('allFilters', $this->filters->allFilters());
    }
}
