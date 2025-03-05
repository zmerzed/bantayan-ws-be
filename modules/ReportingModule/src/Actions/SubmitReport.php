<?php

namespace Kolette\Reporting\Actions;

use Kolette\Reporting\Models\Traits\CanBeReported;
use Kolette\Reporting\Models\Traits\InteractsWithReportableTypes;
use Illuminate\Support\Arr;
use RuntimeException;

class SubmitReport
{
    use InteractsWithReportableTypes;

    public function execute(string $type, $reportId, int $reasonId, string $description = null, array $attachments = [])
    {
        throw_unless(
            $this->hasReportableType($type),
            RuntimeException::class,
            "The type {$type} is not valid."
        );

        $class = $this->getReportableType($type);

        $trait = CanBeReported::class;

        throw_unless(
            Arr::has(class_uses_recursive($class), CanBeReported::class),
            RuntimeException::class,
            "The ${class} does not implement the required trait: ${trait}"
        );

        $instance = new $class;

        /** @var CanBeReported $model */
        $model = $instance->query()->findOrFail($reportId);

        return $model->report($reasonId, $description, $attachments);
    }
}
