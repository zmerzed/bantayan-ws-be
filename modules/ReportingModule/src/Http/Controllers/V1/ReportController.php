<?php

namespace Kolette\Reporting\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Kolette\Reporting\Actions\SubmitReport;
use Kolette\Reporting\Http\Requests\ReportRequest;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(ReportRequest $request, SubmitReport $submitReport): JsonResponse
    {
        $submitReport->execute(
            $request->input('report_type'),
            $request->input('report_id'),
            $request->input('reason_id'),
            $request->input('description'),
            $request->has('photos') ? $request->file('photos', []) : $request->file('attachments', [])
        );

        return $this->respondWithEmptyData();
    }
}
