<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use Illuminate\Http\JsonResponse;


class UploadController extends Controller
{
    public function __invoke(UploadRequest $request): JsonResponse
    {
        $path = $request->file('image')->store('uploads', 'public');
        return response()->json([
            'message' => 'Image uploaded successfully.',
            'path' => $path,
        ]);
    }
}
