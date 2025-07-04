<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin File */
class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $url = \Illuminate\Support\Facades\File::extension($this->path) === 'pdf' ?
            null : route('files.preview', ['file' => $this->id]);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'url' => $url,
        ];
    }
}
