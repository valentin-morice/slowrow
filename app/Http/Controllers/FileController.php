<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;

class FileController extends Controller
{
    public function index()
    {
        return FileResource::collection(File::all());
    }

    public function store(FileRequest $request)
    {
        return new FileResource(File::create($request->validated()));
    }

    public function destroy(File $file)
    {
        $file->delete();

        return response()->json();
    }
}
