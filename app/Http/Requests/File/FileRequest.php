<?php

namespace App\Http\Requests\File;

use App\Enums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:4096'],
            'type' => ['required', Rule::enum(Enums\FileType::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
