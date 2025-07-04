<?php

namespace App\Http\Requests\File;

use App\Enums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'type' => ['required', Rule::enum(Enums\FileType::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
