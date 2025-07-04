<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\StoreFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;

class FileController extends Controller
{
    public function index()
    {
        return FileResource::collection(File::all());
    }

    public function store(StoreFileRequest $request)
    {
        return new FileResource(File::create($request->validated()));
    }

    public function destroy(File $file)
    {
        $file->delete();

        return response()->json();
    }
}
