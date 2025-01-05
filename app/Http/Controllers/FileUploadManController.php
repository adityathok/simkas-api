<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\FileUploadMan;

class FileUploadManController extends Controller
{

    public function stream($guide)
    {
        // Cari file berdasarkan guide 
        $fileUpload = FileUploadMan::where('guide', $guide)->firstOrFail();

        // Dapatkan path file
        $path = $fileUpload->path;

        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Stream file 
        return response()->stream(function () use ($path) {
            $stream = Storage::disk('public')->readStream($path);
            fpassthru($stream);
        }, 200, [
            'Content-Type' => Storage::disk('public')->mimeType($path),
            'Content-Length' => Storage::disk('public')->size($path),
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }
}
