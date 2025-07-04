<?php

namespace App\Http\Requests\File;

class StoreFileRequest extends BaseFileRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'file' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:4096'],
        ]);
    }
}
