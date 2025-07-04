<?php

namespace App\Http\Controllers;

use App\Actions\StoreFileAction;
use App\Http\Requests\File\FileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function index()
    {
        return FileResource::collection(File::all());
    }

    public function store(FileRequest $request, StoreFileAction $action)
    {
        return $action->handle($request);
    }

    public function destroy(File $file)
    {
        $file->delete();

        return response()->json();
    }

    public function preview(File $file): StreamedResponse|JsonResponse
    {
        $file_path = $file->path;

        if (!Storage::exists($file_path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return Storage::response($file_path, null, [
            'Cache-Control' => 'max-age=31536000, public',
        ]);
    }
}
