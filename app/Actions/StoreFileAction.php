<?php

namespace App\Actions;

use App\Http\Requests\File\FileRequest;
use App\Http\Resources\File\FileResource;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;

class StoreFileAction
{
    public function handle(FileRequest $request)
    {
        $data = $request->validated();

        /** @var UploadedFile $uploaded_file */
        $uploaded_file = $data['file'];

        $path = $uploaded_file->storeAs(
            path: 'files',
            name: Str::random(28) . '.' . FileFacade::guessExtension($uploaded_file),
        );

        $file = File::create([
            'name' => $uploaded_file->getClientOriginalName(),
            'type' => $data['type'],
            'path' => $path,
        ]);

        return new FileResource($file);
    }
}
