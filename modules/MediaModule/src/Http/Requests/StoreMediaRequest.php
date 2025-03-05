<?php

namespace Kolette\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|image'
        ];
    }
}
