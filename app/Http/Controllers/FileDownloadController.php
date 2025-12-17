<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileDownloadController extends Controller
{
    public function download(Request $request): StreamedResponse
    {
        $path = $request->query('path');

        if (!$path || str_contains($path, '..')) {
            abort(404);
        }

        if (!Storage::exists($path)) {
            abort(404);
        }

        $filename = basename($path);

        return Storage::download($path, $filename);
    }
}
