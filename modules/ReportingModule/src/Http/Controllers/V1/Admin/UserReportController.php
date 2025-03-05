<?php

namespace Kolette\Reporting\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use Kolette\Auth\Models\User;
use Kolette\Reporting\Http\Resources\UserReportResource;
use Kolette\Reporting\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $collection = QueryBuilder::for(Report::class)
            ->allowedIncludes('attachments', 'reason', 'reporter')
            ->allowedSorts('created_at')
            ->allowedFilters([
                AllowedFilter::scope('search')->ignore(null),
            ])
            ->with('reportable')
            ->hasReportType((new User())->getMorphClass())
            ->defaultSort('-created_at')
            ->paginate();

        return UserReportResource::collection($collection);
    }
}
