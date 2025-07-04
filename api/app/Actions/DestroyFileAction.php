<?php

namespace App\Actions;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class DestroyFileAction
{
    public function handle(File $file): void
    {
        $path = $file->path;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        $file->delete();
    }
}
