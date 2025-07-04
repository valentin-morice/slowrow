<?php

namespace App\Http\Controllers;

use App\Actions\DestroyFileAction;
use App\Actions\StoreFileAction;
use App\Http\Requests\File\FileRequest;
use App\Http\Resources\File\FileCollection;
use App\Http\Resources\File\FileResource;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function index(): FileCollection
    {
        return new FileCollection(File::all());
    }

    public function store(FileRequest $request, StoreFileAction $action): FileResource
    {
        $file = $action->handle($request);

        return new FileResource($file);
    }

    public function destroy(File $file, DestroyFileAction $action): JsonResponse
    {
        $action->handle($file);

        return response()->json();
    }

    public function preview(File $file): StreamedResponse|JsonResponse
    {
        $file_path = $file->path;

        if (!Storage::exists($file_path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return Storage::response($file_path);
    }
}
