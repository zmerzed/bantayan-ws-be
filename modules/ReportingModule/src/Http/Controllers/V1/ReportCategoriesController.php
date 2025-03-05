<?php

namespace Kolette\Reporting\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Kolette\Reporting\Http\Resources\ReportCategoriesResource;
use Kolette\Reporting\Models\ReportCategories;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collection = QueryBuilder::for(ReportCategories::class)
            ->when(!$request->has('filter.type'), function ($query) {
                $query->whereNull('type');
            })
            ->allowedFilters(AllowedFilter::exact('type')->default(null))
            ->get();

        return ReportCategoriesResource::collection($collection);
    }

    public function refundReasons()
    {
        $collection = QueryBuilder::for(ReportCategories::class)
            ->whereType('refund')
            ->get();

        return ReportCategoriesResource::collection($collection);
    }
}
