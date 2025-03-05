<?php

namespace Kolette\Reporting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IssueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject' => ['required'],
            'description' => ['nullable', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['image'],
        ];
    }
}
