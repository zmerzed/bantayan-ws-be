<?php

namespace Kolette\Reporting\Http\Requests;

use Kolette\Reporting\Models\Traits\InteractsWithReportableTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
{
    use InteractsWithReportableTypes;

    public function rules(): array
    {
        return [
            'report_type' => ['bail', 'required', 'string', Rule::in(array_keys($this->getReportableTypes()))],
            'report_id' => ['bail', 'required'],
            'reason_id' => ['bail', 'required', 'exists:report_categories,id'],
            'description' => ['bail', 'nullable', 'string'],
            'attachments' => ['bail', 'nullable', 'array'],
            'attachments.*' => ['image'],
            'photos' => ['bail', 'nullable', 'array'],
            'photos.*' => ['image'],
        ];
    }
}
