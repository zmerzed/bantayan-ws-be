<?php

namespace Kolette\Reporting\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Kolette\Reporting\Http\Requests\IssueRequest;
use Kolette\Reporting\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(IssueRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $issue = Issue::create([...$request->validated(), 'reported_by' => auth()->id()]);

            foreach ($request->file('attachments', []) as $attachment) {
                $issue->addMedia($attachment)->toMediaCollection(Issue::MEDIA_COLLECTION_ATTACHMENTS);
            }
        });

        return $this->respondWithEmptyData();
    }
}
