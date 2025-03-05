<?php

namespace Kolette\Reporting\Models\Traits;

use Kolette\Auth\Models\User;
use Kolette\Reporting\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

trait CanBeReported
{
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function report(int $reasonId, string $description = null, array $attachments = []): Report
    {
        return $this->reportAsUser(auth()->user(), $reasonId, $description, $attachments);
    }

    public function reportAsUser(User $user, int $reasonId, string $description = null, array $attachments = []): Report
    {
        return DB::transaction(function () use ($user, $reasonId, $description, $attachments) {
            $report = new Report();
            $report->reason_id = $reasonId;
            $report->description = $description;
            $report->reported_by = $user->getKey();

            $this->reports()->save($report);

            foreach ($attachments as $attachment) {
                $report->addMedia($attachment)->toMediaCollection(Report::MEDIA_COLLECTION_ATTACHMENTS);
            }

            return $report;
        });
    }

    /**
     * Check if is reported by current authenticated user.
     */
    public function isReported(): bool
    {
        return $this->isReportedAsUser(auth()->user());
    }

    /**
     * Check if reported by user.
     */
    public function isReportedAsUser(User $user): bool
    {
        return $this->reports()->whereReportedBy($user->getKey())->exists();
    }

    /**
     * Filter reported by current user.
     */
    public function scopeAppendIsReported(Builder $query): void
    {
        $query->appendIsReportedAsUser(auth()->user());
    }

    /**
     * Filter reported by specified user.
     */
    public function scopeAppendIsReportedAsUser(Builder $query, User $user): void
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select($query->qualifyColumn('*'));
        }

        $query->addSelect([
            'is_reported' => ($reportQuery = Report::query())
                ->selectRaw('count(id) as is_reported')
                ->where('reported_by', $user->getKey())
                ->where($reportQuery->qualifyColumn('reportable_type'), $this->getMorphClass())
                ->whereColumn($reportQuery->qualifyColumn('reportable_id'), $this->qualifyColumn($this->getKeyName()))
                ->take(1),
        ]);

        $query->withCasts([
            'is_reported' => 'boolean',
        ]);
    }
}
