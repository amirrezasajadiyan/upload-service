<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(UploadRequest $request)
    {
        $path = $request->file('image')->store('uploads', 'public');
        return response()->json([
            'message' => 'Image uploaded successfully.',
            'path' => $path,
        ]);
    }

}
