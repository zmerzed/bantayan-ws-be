<?php

namespace Kolette\Reporting\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use Kolette\Auth\Models\User;
use Kolette\Reporting\Http\Resources\ReportResource;
use Kolette\Reporting\Models\Report;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $collection = QueryBuilder::for(Report::class)
            ->with([
                'reportable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        User::class => ['avatar'],
                    ]);
                },
            ])
            ->allowedIncludes('attachments', 'reason', 'reporter')
            ->allowedSorts('created_at')
            ->allowedFilters([
                AllowedFilter::scope('search')->ignore(null),
                AllowedFilter::scope('type', 'hasReportType'),
            ])
            ->defaultSort('-created_at')
            ->paginate();

        return ReportResource::collection($collection);
    }
}
